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
 * 「定期注文が配送された」
 * Class PeriodicOrderShipped
 * @package App\Events
 * @author k.yamamoto@balocco.info
 */
class PeriodicOrderShipped implements GetOrder,GetCustomer
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var Order
     */
    public $order;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order=$order;
    }

    /**
     * @inheritDoc
     */
    public function getCustomer(): Customer
    {
        return $this->order->customer;
    }

    /**
     * @inheritDoc
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
