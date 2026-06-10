<?php

namespace App\Services;

use App\Models\Report;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class ReportService
{
    /**
     * Optimasi gambar: resize max 1200px, kompres 70%, simpan ke storage public
     *
     * @param UploadedFile $image
     * @return string path file yang tersimpan
     */
    private function optimizeAndStore(UploadedFile $image): string
    {
        // 1. Decode gambar dari file upload
        $img = Image::decode($image->getPathname());

        // 2. Resize jika lebar > 1200px (pertahankan rasio)
        $maxWidth = 1200;
        if ($img->width() > $maxWidth) {
            $img->resize($maxWidth, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }

        // 3. Buat nama file unik
        $extension = $image->extension();
        $filename = 'reports/' . uniqid() . '.' . $extension;
        $fullPath = Storage::disk('public')->path($filename);

        // 4. SIMPAN LANGSUNG dengan kualitas 70%
        //    Fungsi save() secara otomatis menangani encoding berdasarkan ekstensi file.
        $img->save($fullPath, quality: 70);

        return $filename;
    }

    // --- Method createReport dan updateReport tetap sama seperti sebelumnya ---
    public function createReport(array $data, User $user, ?array $images = null): Report
    {
        $data['status'] = 'pending';

        $report = $user->reports()->create($data);

        if ($images && count($images) > 0) {
            foreach ($images as $image) {
                if ($image instanceof UploadedFile) {
                    $path = $this->optimizeAndStore($image);
                    $report->images()->create(['path' => $path]);
                }
            }
        }

        return $report;
    }

    public function updateReport(Report $report, array $data, $newImages = null, array $deletedImageIds = [])
    {
        return DB::transaction(function () use ($report, $data, $newImages, $deletedImageIds) {
            // Update teks laporan
            $report->update([
                'title' => $data['title'],
                'description' => $data['description'],
                'location' => $data['location'],
            ]);

            // Hapus gambar yang dipilih
            if (! empty($deletedImageIds)) {
                $imagesToDelete = $report->images()->whereIn('id', $deletedImageIds)->get();
                foreach ($imagesToDelete as $image) {
                    Storage::disk('public')->delete($image->path);
                    $image->delete();
                }
            }

            // Tambah gambar baru (dengan optimasi)
            if ($newImages) {
                foreach ($newImages as $image) {
                    if ($image instanceof UploadedFile) {
                        $path = $this->optimizeAndStore($image);
                        $report->images()->create(['path' => $path]);
                    }
                }
            }

            return $report->load('images');
        });
    }
}