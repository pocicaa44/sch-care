<?php

namespace App\Events;

use App\Models\Report;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewReportEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $report;

    public function __construct(Report $report)
    {
        $this->report = $report;
    }

    public function broadcastWith()
    {
        return [
            'report' => [
                'id' => $this->report->id,
                'title' => $this->report->title,
                'description' => $this->report->description,
                'location' => $this->report->location,
                'status' => $this->report->status,
                'created_at' => $this->report->created_at->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s'),
                'user' => [
                    'name' => $this->report->user->name,
                ],
            ],
        ];
    }

    public function broadcastOn()
    {
        return new Channel('reports');
    }

    public function broadcastAs()
    {
        return 'new-report';
    }
}
