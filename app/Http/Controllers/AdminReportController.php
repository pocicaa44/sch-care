<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminReportController extends Controller
{

    public function index()
    {
        $stats = [
            'total'    => Report::visibleToAdmin()->count(),
            'pending'  => Report::visibleToAdmin()->where('status', 'pending')->count(),
            'diproses' => Report::visibleToAdmin()->where('status', 'diproses')->count(),
            'selesai'  => Report::visibleToAdmin()->where('status', 'selesai')->count(),
            'ditolak'  => Report::visibleToAdmin()->where('status', 'ditolak')->count(),
        ];        

        $reports = Report::visibleToAdmin()->with('user')->latest()->paginate(6);
        return view('admin.dashboard', compact('reports', 'stats'));
    }

    public function show($id)
    {
        $report = Report::visibleToAdmin()->with(['user', 'comments.user'])->findOrFail($id);
        if ($report->status === "pending") {
            $report->update(['status' => 'diproses']);
        }
        return view('admin.show', compact('report'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:ditolak,selesai',
        ]);

        $report = Report::findOrFail($id);
        $report->update(['status' => $request->status]);

        return back()->with('success', 'Status laporan berhasil diperbarui.');
    }

    public function comment(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        Comment::create([
            'report_id' => $id,
            'user_id' => Auth::id(),
            'content' => $request->content,
        ]);

        return back()->with('success', 'Komentar berhasil ditambahkan.');
    }
    
    public function destroy($id)
    {
        $report = Report::findOrFail($id);
        $report->deleteByAdmin();

        return redirect()->route('admin.dashboard')->with('success', 'Laporan berhasil dihapus.');
    }
}
