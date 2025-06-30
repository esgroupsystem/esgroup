<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Queue\SerializesModels;

class JobOrderCreated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $message;

    /**
     * Create a new event instance.
     */
    public function __construct($message)
    {
        $this->message = $message;
    }

    /**
     * The event's broadcast name (you can customize this).
     */
    public function broadcastAs()
    {
        return 'joborder.created';
    }

    /**
     * The channel the event should broadcast on.
     */
    public function broadcastOn()
    {
        return new Channel('joborder-channel'); // public channel
        // Or use: new PrivateChannel('joborder-channel') if you want it authenticated
    }
}
