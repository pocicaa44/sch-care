<?php

namespace App\Notifications;

use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\Resources\Notification as FcmNotification;

class ReportStatusUpdated extends Notification
{
    use Queueable;

    protected Report $report;

    /**
     * Create a new notification instance.
     */
    public function __construct(Report $report)
    {
        $this->report = $report;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['FcmChannel::class'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toFcm(object $notifiable): FcmMessage
    {
        $statusText = match ($this->report->status) {
            'pending' => 'menunggu verifikasi',
            'diproses' => 'sedang diproses oleh admin',
            'selesai' => 'telah selesai',
            'ditolak' => 'ditolak',
            default => $this->report->status,
        };

        return new FcmMessage(
            data: [
                'type' => 'status_update',
                'report_id' => (string) $this->report->id,
                'status' => $this->report->status,
            ],
            notification: new FcmNotification(
                title: 'Status Laporan Berubah',
                body: "Laporan \"{$this->report->title}\" {$statusText}",
            )
        );
    }
}
