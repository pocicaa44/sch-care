<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Response;
use App\Notifications\NewResponse;
use App\Notifications\ReportStatusUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminReportController extends Controller
{
    public function index(Request $request)
    {
        $statusFilter = $request->input('status');
        $rawSearch = $request->input('search');

        // Sanitasi dan validasi search
        $searchTerm = null;
        if ($rawSearch) {
            $rawSearch = trim($rawSearch);
            if (strlen($rawSearch) < 2) {
                return back()->with('error', 'Minimal 2 karakter untuk pencarian.');
            }
            // Escape wildcard LIKE
            $searchTerm = str_replace(['%', '_'], ['\%', '\_'], $rawSearch);
            // Batasi panjang
            $searchTerm = substr($searchTerm, 0, 100);
            // Hapus tag HTML
            $searchTerm = strip_tags($searchTerm);
        }

        $query = Report::visibleToAdmin();

        // filter status
        if ($statusFilter && $statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        // search bar
        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', '%'.$searchTerm.'%')
                    ->orWhere('description', 'like', '%'.$searchTerm.'%')
                    ->orWhere('location', 'like', '%'.$searchTerm.'%')
                    ->orWhereHas('user', function ($userQuery) use ($searchTerm) {
                        $userQuery->where('name', 'like', '%'.$searchTerm.'%');
                    });
            });
        }

        $stats = [
            'total' => Report::visibleToAdmin()->count(),
            'pending' => Report::visibleToAdmin()->where('status', 'pending')->count(),
            'diproses' => Report::visibleToAdmin()->where('status', 'diproses')->count(),
            'selesai' => Report::visibleToAdmin()->where('status', 'selesai')->count(),
            'ditolak' => Report::visibleToAdmin()->where('status', 'ditolak')->count(),
        ];

        $reports = $query->with('user')->latest()->paginate(6);

        $reports->appends([
            'status' => $statusFilter,
            'search' => $searchTerm,
        ]);

        return view('admin.dashboard', compact('reports', 'stats', 'statusFilter', 'searchTerm'));
    }

    public function show($id)
    {
        $report = Report::visibleToAdmin()->with(['user', 'responses.user'])->findOrFail($id);

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

    public function storeResponse(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|string',
            'attachments' => 'nullable|array',
            'attachments.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // dd($request->allFiles());

        $report = Report::findOrFail($id);

        if ($report->status === 'pending') {
            $report->update(['status' => 'diproses']);
        }

        $adminResponse = Response::create([
            'report_id' => $id,
            'user_id' => Auth::id(),
            'content' => $request->content,
        ]);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('response-attachments', 'public');
                $adminResponse->attachments()->create(['path' => $path]);
            }
        }

        // 🔔 Kirim notifikasi ke siswa jika ada komentar baru dan user memiliki FCM token
        if ($report->user && $report->user->fcm_token) {
            $report->user->notify(new NewResponse($report, $adminResponse));
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
        $report->responses()->delete();
        // $report->responses()->attachment()->delete();
        $report->forceDelete();

        return redirect()->route('admin.dashboard')->with('success', 'Laporan berhasil dihapus.');
    }
}
