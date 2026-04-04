<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReportRequest;
use App\Http\Requests\UpdateReportRequest;
use App\Http\Resources\ReportResource;
use App\Models\Report;
use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function index()
    {
        $reports = Report::where('user_id', Auth::id())
            ->whereNull('deleted_by_user_at')
            ->with('images')
            ->latest()
            ->get();

        return ReportResource::collection($reports);
    }

    public function store(StoreReportRequest $request, ReportService $service)
    {
        $validated = $request->validated();

        $images = $request->file('images');
        if ($images && !is_array($images)) {
            $images = [$images];
        }

        $report = $service->createReport(
            $validated,
            $request->user(),
            $images,
        );

        return new ReportResource($report);
    }

    public function show($id)
    {
        $report = Report::where('user_id', Auth::id())
            ->whereNull('deleted_by_user_at')
            ->with(['comments.user', 'images'])
            ->findOrFail($id);

        return new ReportResource($report);
    }

    public function update(UpdateReportRequest $request, $id, ReportService $service)
    {
        $report = Report::where('user_id', Auth::id())->findOrFail($id);

        if ($report->status !== 'pending') {
            return response()->json([
                'message' => 'Laporan tidak dapat diedit karena status sudah ' . $report->status
            ], 403);
        }

        $images = $request->file('images');
        if($images && !is_array($images)){
            $images = [$images];
        }

        $updatedReport = $service->updateReport(
            $report,
            $request->validated(),
            $images,
            $request->input('deleted_images', [])
        );

        return new ReportResource($updatedReport);
    }

    public function destroy($id)
    {
        $report = Report::where('user_id', Auth::id())->findOrFail($id);

        if ($report->status === 'diproses') {
            return response()->json(['message' => 'Laporan tidak dapat dihapus karena sedang dalam proses.'], 403);
        }

        $report->deleteByUser();

        if ($report->status === 'pending') {
            $report->deleteByAdmin();
        }

        return response()->json(['message' => 'Laporan berhasil dihapus.']);
    }
}
