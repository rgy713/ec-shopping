<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class JobFailed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $event;

    /**
     * @var \Exception
     */
    public $exception;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($event, $e)
    {
        $this->event = $event;
        $this->exception = $e;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
