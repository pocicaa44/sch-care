<?php

namespace App\Console\Commands;

use App\Models\Report;
use App\Notifications\ReportWillBeDeleted;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AutoDeleteExpiredReports extends Command
{
    protected $signature = 'reports:auto-delete';

    protected $description = 'Sembunyikan laporan selesai/ditolak dari siswa setelah melewati batas auto_delete_days dan kirim pengingat H-1';

    public function handle()
    {
        // 1. Kirim notifikasi H-1 untuk laporan yang akan kadaluwarsa besok
        $this->sendReminders();

        // 2. Soft delete laporan yang sudah melewati masa retensi
        $this->deleteExpiredReports();

        $this->info('Auto-delete reports command completed.');
    }

    /**
     * Kirim notifikasi pengingat H-1 ke siswa yang memiliki FCM token
     */
    protected function sendReminders(): void
    {
        // Laporan yang akan kadaluwarsa besok (status_changed_at + auto_delete_days = hari ini + 1 hari)
        $reports = Report::whereIn('reports.status', ['selesai', 'ditolak'])
            ->whereNotNull('reports.status_changed_at')
            ->whereNull('reports.deleted_by_user_at')
            ->join('users', 'users.id', '=', 'reports.user_id')
            ->select('reports.*', 'users.auto_delete_days', 'users.fcm_token')
            ->get();

        $reminderCount = 0;

        foreach ($reports as $report) {
            $statusChangedAt = $report->status_changed_at instanceof Carbon
                ? $report->status_changed_at
                : Carbon::parse($report->status_changed_at);

            $expiryDate = $statusChangedAt->copy()->addDays($report->auto_delete_days);
            $tomorrow = now()->addDay()->startOfDay();

            // Jika expiry date besok (H-1) dan user punya FCM token
            if ($expiryDate->isSameDay($tomorrow) && $report->fcm_token) {
                $report->user->notify(new ReportWillBeDeleted($report, 1));
                $reminderCount++;
                $this->info("Notifikasi H-1 dikirim untuk laporan ID {$report->id}.");
            }
        }

        $this->info("Notifikasi pengingat dikirim untuk {$reminderCount} laporan.");
    }

    /**
     * Soft delete laporan yang sudah melewati masa retensi
     */
    protected function deleteExpiredReports(): void
    {
        $reports = Report::whereIn('reports.status', ['selesai', 'ditolak'])
            ->whereNotNull('reports.status_changed_at')
            ->whereNull('reports.deleted_by_user_at')
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
                $report->update(['deleted_by_user_at' => now()]);
                $updatedCount++;
                $this->info("Laporan ID {$report->id} disembunyikan dari siswa.");
            }
        }

        $this->info("Berhasil menyembunyikan {$updatedCount} laporan dari siswa.");
    }
}
