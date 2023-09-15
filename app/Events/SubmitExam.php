<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SubmitExam implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $submitExam;

    /**
     * Create a new event instance.
     */
    public function __construct(array $submitExam)
    {
        $this->submitExam = $submitExam;
        Log::info('Submit Exam event fired');
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn()
    {
        return new Channel('teacher-notifications');
    }

    // public function broadcastOn()
    // {
    //     return new Channel('admin-notifications');
    // }

    public function broadcastAs()
    {
        return 'submit-exam';
    }
}
