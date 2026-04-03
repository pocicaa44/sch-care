<?php

namespace App\Services;

use App\Models\Report;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ReportService
{
    public function createReport(array $data, User $user, ?array $images = null): Report
    {
        $data['status'] = 'pending';

        $report = $user->reports()->create($data);

        if ($images && count($images) > 0) {
            foreach ($images as $image) {
                if ($image instanceof UploadedFile) {
                    $path = $image->store('reports', 'public');
                    $report->images()->create(['path' => $path]);
                }
            }
        }

        return $report;
    }

    public function updateReport(Report $report, array $data, $newImages = null, array $deletedImageIds = [])
    {
        return DB::transaction(function () use ($report, $data, $newImages, $deletedImageIds) {
          $report->update([
            'title' => $data['title'],
            'description' => $data['description'],
            'location' => $data['location'],
          ]);

          if(!empty($deletedImageIds)) {
            $imagesToDelete = $report->images()->whereIn('id', $deletedImageIds)->get();
            foreach ($imagesToDelete as $image) {
              Storage::disk('public')->delete($image->path);
              $image->delete();
            }
          }

          if ($newImages) {
            foreach ($newImages as $image) {
              $path = $image->store('reports', 'public');
              $report->images()->create(['path' => $path]);
            }
          }

          return $report->load('images');
        });
    }
}
