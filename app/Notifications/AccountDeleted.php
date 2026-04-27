<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\Notification as FcmNotification;

class AccountDeleted extends Notification
{
    use Queueable;

    public function __construct() {}

    public function via(object $notifiable): array
    {
        return [FcmChannel::class];
    }

    public function toFcm(object $notifiable): FcmMessage
    {
        return new FcmMessage(
            data: [
                'type' => 'account_deleted',
            ],
            notification: new FcmNotification(
                title: 'Akun Dihapus',
                body: 'Akun Anda telah berhasil dihapus. Terima kasih telah menggunakan layanan kami.',
            )
        );
    }
}