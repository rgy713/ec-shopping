<?php

namespace App\Events\Batch;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class BatchFailed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var string 失敗したバッチ処理名
     */
    public $batchName;

    public $errorMessage;

    /**
     * BatchFailed constructor.
     * @param $batchName
     */
    public function __construct($batchName, $errorMessage = null)
    {
        $this->batchName = $batchName;
        $this->errorMessage = $errorMessage;
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
