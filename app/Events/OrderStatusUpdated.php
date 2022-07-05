<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

/**
 * このイベントは、受注ステータスが変更された場合のみ呼び出す。
 * ステータスが変更されているかの判断はイベント呼び出し側で行うこと。
 * Class OrderStatusUpdated
 * @package App\Events
 * @author k.yamamoto@balocco.info
 */
class OrderStatusUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var
     */
    protected $status;

    /**
     * @var Order
     */
    protected $order;

    /**
     * OrderStatusUpdated constructor.
     * @param $status
     */
    public function __construct($status, Order $order)
    {
        $this->status = $status;
        $this->order = $order;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return Order
     */
    public function getOrder(): Order
    {
        return $this->order;
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
