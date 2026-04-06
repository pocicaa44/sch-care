<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Report;
use App\Notifications\NewComment;
use App\Notifications\ReportStatusUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminReportController extends Controller
{
    public function index()
    {
        $stats = [
            'total' => Report::visibleToAdmin()->count(),
            'pending' => Report::visibleToAdmin()->where('status', 'pending')->count(),
            'diproses' => Report::visibleToAdmin()->where('status', 'diproses')->count(),
            'selesai' => Report::visibleToAdmin()->where('status', 'selesai')->count(),
            'ditolak' => Report::visibleToAdmin()->where('status', 'ditolak')->count(),
        ];

        $reports = Report::visibleToAdmin()->with('user')->latest()->paginate(6);

        return view('admin.dashboard', compact('reports', 'stats'));
    }

    public function show($id)
    {
        $report = Report::visibleToAdmin()->with(['user', 'comments.user'])->findOrFail($id);

        return view('admin.show', compact('report'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:ditolak,selesai',
        ]);

        $report = Report::findOrFail($id);
        $oldStatus = $report->status;
        $newStatus = $request->status;

        $report->update(['status' => $newStatus]);

        if (in_array($newStatus, ['selesai', 'ditolak']) && $oldStatus !== $newStatus) {
            $report->update(['status_changed_at' => now()]);
        }

        // 🔔 Kirim notifikasi ke siswa jika status berubah dan user memiliki FCM token
        if ($oldStatus !== $newStatus && $report->user && $report->user->fcm_token) {
            $report->user->notify(new ReportStatusUpdated($report));
        }

        return back()->with('success', 'Status laporan berhasil diperbarui.');
    }

    public function comment(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $report = Report::findOrFail($id);

        if ($report->status === 'pending') {
            $report->update(['status' => 'diproses']);
        }

        $comment = Comment::create([
            'report_id' => $id,
            'user_id' => Auth::id(),
            'content' => $request->content,
        ]);

        // 🔔 Kirim notifikasi ke siswa jika ada komentar baru dan user memiliki FCM token
        if ($report->user && $report->user->fcm_token) {
            $report->user->notify(new NewComment($report, $comment));
        }

        return back()->with('success', 'Komentar berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        $report = Report::findOrFail($id);

        if (is_null($report->deleted_by_user_at)) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Laporan belum dihapus oleh siswa. Tidak bisa dihapus oleh admin.');
        }

        foreach ($report->images as $image) {
            Storage::disk('public')->delete($image->path);
        }
        $report->images()->delete();
        $report->comments()->delete();
        $report->forceDelete();

        return redirect()->route('admin.dashboard')->with('success', 'Laporan berhasil dihapus.');
    }
}
