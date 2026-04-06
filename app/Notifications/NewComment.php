<?php

namespace App\Notifications;

use App\Models\Comment;
use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\Notification as FcmNotification;

class NewComment extends Notification
{
    use Queueable;

    protected Report $report;
    protected Comment $comment;

    public function __construct(Report $report, Comment $comment)
    {
        $this->report = $report;
        $this->comment = $comment;
    }

    public function via(object $notifiable): array
    {
        return [FcmChannel::class];
    }

    public function toFcm(object $notifiable): FcmMessage
    {
        // Ambil nama admin yang berkomentar
        $adminName = $this->comment->user?->name ?? 'Admin';

        // Potong komentar jika terlalu panjang (max 100 karakter)
        $commentPreview = strlen($this->comment->content) > 100 
            ? substr($this->comment->content, 0, 100) . '...' 
            : $this->comment->content;

        return new FcmMessage(
            data: [
                'type' => 'new_comment',
                'report_id' => (string) $this->report->id,
                'comment_id' => (string) $this->comment->id,
            ],
            notification: new FcmNotification(
                title: 'Komentar Baru dari Admin',
                body: "{$adminName}: {$commentPreview}",
            )
        );
    }
}