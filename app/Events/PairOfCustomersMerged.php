<?php

namespace App\Events;

use App\Events\Interfaces\GetCustomer;
use App\Models\Customer;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PairOfCustomersMerged implements GetCustomer
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * 統合により削除される顧客
     * @var Customer
     */
    public $customerToDelete;

    /**
     * 統合先の顧客
     * @var Customer
     */
    public $customerToLive;

    /**
     * PairOfCustomersMerged constructor.
     * @param Customer $customerToLive 統合先顧客
     */
    public function __construct(Customer $customerToDelete,Customer $customerToLive)
    {
        $this->customerToDelete=$customerToDelete;
        $this->customerToLive=$customerToLive;
    }

    public function getCustomer():Customer
    {
        return $this->customerToLive;
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
