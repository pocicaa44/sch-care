<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\ReportImage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    public function index()
    {
        
        // $stats = [
        //     'total'    => Report::count(),
        //     'pending'  => Report::where('status', 'pending')->count(),
        //     'diproses' => Report::where('status', 'diproses')->count(),
        //     'selesai'  => Report::where('status', 'selesai')->count(),
        //     'ditolak'  => Report::where('status', 'ditolak')->count(),
        // ];

        $reports = Report::where('user_id', Auth::id())->with('comments.user')->latest()->paginate(6);
        return view('siswa.dashboard', compact('reports'));
        
    }

    public function create() {
        return view('siswa.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:100',
            'location' => 'required|string|max:1000',
            'description' => 'required|string',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $envidencePath = null;
        if($request->hasFile('evidence')) {
            $envidencePath = $request->file('evidence')->store('evidence', 'public');
        }

        $report = Report::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'location' => $request->location,
            'description' => $request->description,
            'is_anonymous' => $request->has('is_anonymous'),
            'status' => 'pending',
        ]);

        if ($request->hasFile('images')){
            foreach ($request->file('images') as $image){
                $path = $image->store('evidences', 'public');

                ReportImage::create([
                    'report_id' => $report->id,
                    'path' => $path,
                ]);
            }
        }

        return redirect()->route('siswa.dashboard')->with('success', 'Laporan berhasil dibuat.');
    }

    public function destroy($id)
    {
        $report = Report::where('user_id', Auth::id())->findOrFail($id);

        if ($report->status === 'diproses') {
            return back()->with('error', 'Laporan tidak dapat dihapus karena sedang dalam proses.');
        }

        foreach ($report->images as $image) {
            if($image->path && Storage::disk('public')->exists($image->path)) {
                Storage::disk('public')->delete($image->path);
            }
        }

        $report->delete();

        return back()->with('success', 'Laporan berhasil dihapus.');
    }
}