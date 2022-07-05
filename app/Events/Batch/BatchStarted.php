<?php

namespace App\Events\Batch;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class BatchStarted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var string 開始したバッチ処理名
     */
    public $batchName;

    /**
     * BatchStarted constructor.
     * @param $batchName
     */
    public function __construct($batchName)
    {
        $this->batchName=$batchName;
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
