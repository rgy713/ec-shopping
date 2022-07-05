<?php

namespace App\Events;

use App\Models\PeriodicOrder;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PeriodicInfoModifiedByCustomer
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var PeriodicOrder
     */
    protected $periodicOrder;

    /**
     * 変更前の定期データを持つ配列
     * モデルの getOriginal() メソッドの返り値を期待
     * @var array
     */
    protected $periodicOrderOriginal;

    /**
     * 変更後の定期データを持つ配列
     * モデルの getAttributes() メソッドの返り値を期待
     * @var array
     */
    protected $periodicOrderAttributes;

    /**
     * 変更前の定期配送データを持つ配列
     * モデルの getOriginal() メソッドの返り値を期待
     * @var array
     */
    protected $periodicShippingOriginal;


    /**
     * 変更後の定期配送データを持つ配列
     * モデルの getAttributes() メソッドの返り値を期待
     * @var array
     */
    protected $periodicShippingAttributes;

    /**
     * PeriodicInfoModifiedByCustomer constructor.
     * @param PeriodicOrder $periodicOrder
     * @param array $periodicOrderOriginal
     * @param array $periodicOrderAttributes
     * @param array $periodicShippingOriginal
     * @param array $periodicShippingAttributes
     */
    public function __construct(
        PeriodicOrder $periodicOrder,
        array $periodicOrderOriginal,
        array $periodicOrderAttributes,
        array $periodicShippingOriginal,
        array $periodicShippingAttributes
    ) {
        $this->periodicOrder = $periodicOrder;
        $this->periodicOrderOriginal = $periodicOrderOriginal;
        $this->periodicOrderAttributes = $periodicOrderAttributes;
        $this->periodicShippingOriginal = $periodicShippingOriginal;
        $this->periodicShippingAttributes = $periodicShippingAttributes;
    }

    /**
     * @return PeriodicOrder
     */
    public function getPeriodicOrder(): PeriodicOrder
    {
        return $this->periodicOrder;
    }

    /**
     * @return array
     */
    public function getPeriodicOrderOriginal(): array
    {
        return $this->periodicOrderOriginal;
    }

    /**
     * @return array
     */
    public function getPeriodicOrderAttributes(): array
    {
        return $this->periodicOrderAttributes;
    }

    /**
     * @return array
     */
    public function getPeriodicShippingOriginal(): array
    {
        return $this->periodicShippingOriginal;
    }

    /**
     * @return array
     */
    public function getPeriodicShippingAttributes(): array
    {
        return $this->periodicShippingAttributes;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
