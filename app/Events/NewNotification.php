<?php

namespace App\Events;

use App\Models\Notification;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewNotification implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $notification;

    /**
     * Create a new event instance.
     */
    public function __construct(Notification $notification)
    {
        $this->notification = $notification;
    }

    /**
     * The channel the event should broadcast on.
     */
    public function broadcastOn()
    {
        // broadcast only to the user who owns the notification
        return new PrivateChannel('user.' . $this->notification->user_id);
    }

    /**
     * Optional: event name
     */
    public function broadcastAs()
    {
        return 'NewNotification';
    }
}
