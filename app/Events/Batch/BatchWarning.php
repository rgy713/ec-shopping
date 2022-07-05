<?php

namespace App\Events\Batch;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class BatchWarning
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var string batchname
     */
    public $batchName;

    /**
     * @var string exception
     */
    public $exception;

    /**
     * BatchBatchWarning constructor.
     * @param $batchName
     */
    public function __construct($batchName, $exception)
    {
        $this->batchName=$batchName;
        $this->exception=$exception;
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
