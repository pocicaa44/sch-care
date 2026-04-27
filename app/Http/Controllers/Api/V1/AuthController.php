<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Notifications\AccountDeleted;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 442);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'siswa',
        ]);

        $token = $user->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 442);
        }

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        if ($user->role !== 'siswa') {
            return response()->json(['message' => 'authorized. Only student accounts can use the mobile app'], 403);
        }

        $token = $user->createToken('mobile-app')->plainTextToken;

        return response()->json(['token' => $token, 'user' => $user]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->deleteCurrent();

        return response()->json(['message' => 'Logged out successfully']);
    }

    public function deleteAccount(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        $user = $request->user();

        // Verifikasi password
        if (! Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Password salah',
            ], 422);
        }

        if ($user->fcm_token) {
            $user->notify(new AccountDeleted);
        }

        DB::transaction(function () use ($user) {
            // Soft delete semua laporan user (dari sisi siswa)
            $user->reports()->whereNull('deleted_by_user_at')->update([
                'deleted_by_user_at' => now(),
            ]);

            // Hard delete komentar user
            $user->comments()->delete();

            // Soft delete akun user (menggunakan SoftDeletes trait)
            $user->delete();
        });

        // Revoke semua token user (logout dari semua perangkat)
        $user->tokens()->delete();

        return response()->json([
            'message' => 'Akun berhasil dihapus. Terima kasih.',
        ], 200);
    }

    public function updateFcmToken(Request $request)
    {
        $request->validate([
            'fcm_token' => 'required|string',
        ]);

        $user = $request->user();
        $user->fcm_token = $request->fcm_token;
        $user->save();

        return response()->json(['message' => 'FCM token updated successfully.']);
    }
}
