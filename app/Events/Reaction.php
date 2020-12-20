<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class Reaction implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $reaction, $message, $up;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($reaction, $message, $up)
    {
        $this->reaction = $reaction;
        $this->message = $message;
        $this->up = $up;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return [
            new Channel('channel-' . $this->message->user_id),
            new Channel('channel-message-' . $this->message->id)
        ];
    }
    public function broadcastAs()
    {
        if ($this->up) {
            return 'notification-push';
        } else {
            return 'notification';
        }
    }
}
