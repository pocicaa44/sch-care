<?php

namespace App\Notifications;

use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\Notification as FcmNotification;

class ReportWillBeDeleted extends Notification
{
    use Queueable;

    protected Report $report;
    protected int $daysLeft;

    public function __construct(Report $report, int $daysLeft)
    {
        $this->report = $report;
        $this->daysLeft = $daysLeft;
    }

    public function via(object $notifiable): array
    {
        return [FcmChannel::class];
    }

    public function toFcm(object $notifiable): FcmMessage
    {
        $dayText = $this->daysLeft === 1 ? 'hari' : 'hari';

        return new FcmMessage(
            data: [
                'type' => 'auto_delete_reminder',
                'report_id' => (string) $this->report->id,
                'days_left' => (string) $this->daysLeft,
            ],
            notification: new FcmNotification(
                title: 'Pengingat: Laporan Akan Dihapus',
                body: "Laporan \"{$this->report->title}\" akan dihapus secara otomatis dalam {$this->daysLeft} {$dayText}",
            )
        );
    }
}