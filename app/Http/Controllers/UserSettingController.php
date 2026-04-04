<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Report;

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
}
