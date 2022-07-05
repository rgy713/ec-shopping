<?php

namespace App\Events;

use App\Events\Interfaces\GetCustomer;
use App\Events\Interfaces\GetOrder;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

/**
 * 「注文が確定した」
 * Class OrderConfirmed
 * @package App\Events
 * @author k.yamamoto@balocco.info
 */
class OrderConfirmed implements GetCustomer, GetOrder
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var Order
     */
    protected $order;

    /**
     * 通知を行うかどうか
     * @var boolean
     */
    protected $notifyFlag;

    /**
     * OrderConfirmed constructor.
     * @param Order $order
     * @param $notifyFlag
     */
    public function __construct(Order $order, $notifyFlag)
    {
        $this->order = $order;
        $this->notifyFlag = $notifyFlag;

    }

    public function getCustomer(): Customer
    {
        return $this->order->customer;
    }

    /**
     * @return Order
     */
    public function getOrder(): Order
    {
        return $this->order;
    }

    /**
     * @return bool
     */
    public function getNotifyFlag(): bool
    {
        return $this->notifyFlag;
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
