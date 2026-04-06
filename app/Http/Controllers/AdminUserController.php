<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'siswa')->withTrashed()->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function destroy($id)
    {
        $user = User::where('role', 'siswa')->findOrFail($id);

        DB::transaction(function () use ($user) {
            // Soft delete laporan user
            $user->reports()->whereNull('deleted_by_user_at')->update([
                'deleted_by_user_at' => now()
            ]);
            $user->comments()->delete();
            $user->delete();
        });

        return redirect()->route('admin.users.index')->with('success', 'Akun siswa berhasil dihapus.');
    }

    public function restore($id)
    {
        $user = User::withTrashed()->where('role', 'siswa')->findOrFail($id);
        $user->restore();

        // Tidak perlu restore laporan, biarkan tetap soft delete dari siswa? 
        // Terserah kebijakan. Saya sarankan tidak restore laporan agar tidak muncul lagi.
        // Jika ingin restore laporan juga, tambahkan:
        // $user->reports()->whereNotNull('deleted_by_user_at')->update(['deleted_by_user_at' => null]);

        return redirect()->route('admin.users.index')->with('success', 'Akun siswa berhasil dipulihkan.');
    }
}