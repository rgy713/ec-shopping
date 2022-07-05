<?php

namespace App\Listeners;

use App\Events\OrderAccounted;
use App\Events\OrderCanceled;
use App\Events\OrderShipped;
use App\Events\OrderStatusUpdated;
use App\Events\ShippingScheduled;
use App\Services\OrderService;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Class OrderStatusUpdatedObserver
 * @package App\Listeners
 * @author k.yamamoto@balocco.info
 */
class OrderStatusUpdatedObserver
{
    /**
     * @var OrderService
     */
    protected $orderService;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(OrderService $orderService)
    {
        $this->orderService=$orderService;
    }

    /**
     * TODO:実装
     * @param OrderStatusUpdated $event
     * @author k.yamamoto@balocco.info
     */
    public function handle(OrderStatusUpdated $event)
    {
        //計上売上にカウントするステータスとなった
        if(in_array($event->getStatus(),$this->orderService->getAccountedStatuses())){
            event(new OrderAccounted($event->getOrder()));
        }

        //配送手配が完了した
        if(in_array($event->getStatus(),$this->orderService->getShippingScheduledStatuses())){
            event(new ShippingScheduled($event->getOrder()));
        }

        //発送が完了した
        if(in_array($event->getStatus(),$this->orderService->getShippedStatuses())){
            event(new OrderShipped($event->getOrder()));
        }

        //キャンセルされた
        if(in_array($event->getStatus(),$this->orderService->getCanceledStatuses())){
            event(new OrderCanceled($event->getOrder()));

        }

    }
}
