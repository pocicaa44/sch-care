<?php

namespace App\Console\Commands;

use App\Models\Report;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AutoDeleteExpiredReports extends Command
{
    protected $signature = 'reports:auto-delete';
    protected $description = 'Sembunyikan laporan selesai/ditolak dari siswa setelah melewati batas auto_delete_days';

    public function handle()
    {
        // Ambil laporan yang memenuhi syarat dan belum dihapus oleh siswa (deleted_by_user_at masih null)
        $reports = Report::whereIn('reports.status', ['selesai', 'ditolak'])
            ->whereNotNull('reports.status_changed_at')
            ->whereNull('reports.deleted_by_user_at')   // hanya yang masih tampak di siswa
            ->join('users', 'users.id', '=', 'reports.user_id')
            ->select('reports.*', 'users.auto_delete_days')
            ->get();

        $updatedCount = 0;

        foreach ($reports as $report) {
            $statusChangedAt = $report->status_changed_at instanceof Carbon 
                ? $report->status_changed_at 
                : Carbon::parse($report->status_changed_at);
            
            $expiryDate = $statusChangedAt->addDays($report->auto_delete_days);
            
            if (now()->gte($expiryDate)) {
                // Soft delete dari sisi siswa (sembunyikan dari siswa)
                $report->update(['deleted_by_user_at' => now()]);
                $updatedCount++;
                $this->info("Laporan ID {$report->id} disembunyikan dari siswa.");
            }
        }

        $this->info("Berhasil menyembunyikan {$updatedCount} laporan dari siswa.");
    }
}