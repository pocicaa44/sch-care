<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Notifications\AccountDeleted;

class UserSettingController extends Controller
{
    public function edit()
    {
        $user = Auth::user();

        return view('siswa.settings', compact('user'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'auto_delete_days' => 'required|integer|in:3,7,14,30',
        ]);

        $user = Auth::user();
        $user->update(['auto_delete_days' => $request->auto_delete_days]);

        return redirect()->route('siswa.settings.edit')
            ->with('success', 'Pengaturan berhasil diubah. Laporan selesai/ditolak akan otomatis dihapus setelah '.$request->auto_delete_days.' hari.');
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'password' => 'required|current_password',
        ]);

        $user = Auth::user();

        if ($user->fcm_token) {
            $user->notify(new AccountDeleted);
        }

        DB::transaction(function () use ($user) {
            // Soft delete semua laporan milik user (dari sisi siswa)
            $user->reports()->whereNull('deleted_by_user_at')->update([
                'deleted_by_user_at' => now(),
            ]);

            // Hapus komentar user (hard delete)
            $user->comments()->delete();

            // Soft delete akun user (menggunakan SoftDeletes trait)
            $user->delete();
        });

        // Logout user setelah akun dihapus
        Auth::logout();

        return redirect('/')->with('success', 'Akun Anda telah dihapus. Terima kasih.');
    }
}
