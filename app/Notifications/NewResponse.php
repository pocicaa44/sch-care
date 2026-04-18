<?php

namespace App\Notifications;

use App\Models\Report;
use App\Models\Response;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\Notification as FcmNotification;

class NewResponse extends Notification
{
    use Queueable;

    protected Report $report;
    protected Response $adminResponse;

    public function __construct(Report $report, Response $adminResponse)
    {
        $this->report = $report;
        $this->adminResponse = $adminResponse;
    }

    public function via(object $notifiable): array
    {
        return [FcmChannel::class];
    }

    public function toFcm(object $notifiable): FcmMessage
    {
        // Ambil nama admin yang berkomentar
        $adminName = $this->adminResponse->user?->name ?? 'Admin';

        // Potong komentar jika terlalu panjang (max 100 karakter)
        $responsePreview = strlen($this->adminResponse->content) > 100 
            ? substr($this->adminResponse->content, 0, 100) . '...' 
            : $this->adminResponse->content;

        return new FcmMessage(
            data: [
                'type' => 'new_response',
                'report_id' => (string) $this->report->id,
                'response_id' => (string) $this->adminResponse->id,
            ],
            notification: new FcmNotification(
                title: 'Komentar Baru dari Admin',
                body: "{$adminName}: {$responsePreview}",
            )
        );
    }
}
