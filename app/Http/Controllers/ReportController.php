<?php

namespace App\Http\Controllers;

use App\Events\NewReportEvent;
use App\Events\ReportUpdatedEvent;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Http\Requests\StoreReportRequest;
use App\Http\Requests\UpdateReportRequest;
use App\Models\Report;
use App\Services\ReportService;
use Illuminate\Support\Facades\Auth;
use Laravel\Reverb\Loggers\Log;

class ReportController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index()
    {
        $reports = Report::where('user_id', Auth::id())->visibleToUser()->with('responses.user')->latest()->paginate(6);

        return view('siswa.dashboard', compact('reports'));
    }

    public function create()
    {
        return view('siswa.create');
    }

    public function show($id)
    {
        $report = Report::where('user_id', Auth::id())->visibleToUser()->with(['responses.user', 'images'])->findOrFail($id);

        return view('siswa.show', compact('report'));
    }

    public function store(StoreReportRequest $request, ReportService $service)
    {
        $images = $request->file(['images']);

        if ($images && !is_array($images)) {
            $images = [$images];
        }

        $report = $service->createReport(
            $request->validated(),
            $request->user(),
            $images,
        );

        Log::info('Event NewReportEvent dipanggil untuk report ID: ' . $report->id);
        event(new NewReportEvent($report));

        return redirect()->route('siswa.dashboard')->with('success', 'Laporan berhasil dibuat');
    }

    public function edit($id)
    {
        $report = Report::where('user_id', Auth::id())->with('images')->findOrFail($id);

        $this->authorize('update', $report);

        return view('siswa.edit', compact('report'));
    }

    public function update(UpdateReportRequest $request, $id, ReportService $service)
    {
        $report = Report::where('user_id', Auth::id())->findOrFail($id);
        $this->authorize('update', $report);

        // Ambil file dari input 'images[]' (sama seperti store)
        $images = $request->file('images');
        if ($images && !is_array($images)) {
            $images = [$images];
        }

        $updatedReport = $service->updateReport(
            $report,
            $request->validated(),          // title, description, location
            $images,                        // file gambar baru (array)
            $request->input('deleted_images', []) // ID gambar yang dihapus
        );

        event(new ReportUpdatedEvent($report));

        return redirect()->route('siswa.show', $updatedReport->id)
            ->with('success', 'Laporan berhasil diperbarui')
            ->with('replace', true);
    }

    public function destroy($id)
    {
        $report = Report::where('user_id', Auth::id())->findOrFail($id);

        if ($report->status === 'diproses') {
            return back()->with('error', 'Laporan tidak dapat dihapus karena sedang dalam proses.');
        }

        $report->deleteByUser();

        if ($report->status === 'pending') {
            $report->deleteByAdmin();
        }

        return redirect()->route('siswa.dashboard')->with('success', 'Laporan berhasil dihapus.')->with('replace', true);
    }
}
