<?php

namespace App\Listeners;

use App\Events\ShippingScheduled;
use App\Models\Shipping;
use App\Services\Delivery;
use Carbon\Carbon;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * 出荷予定日
 * 到著予定日
 * を記録する
 * Class SaveScheduleDate
 * @package App\Listeners
 * @author k.yamamoto@balocco.info
 */
class SaveScheduleDate
{
    /**
     * @var Delivery
     */
    protected $deliveryService;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Delivery $deliveryService)
    {
        $this->deliveryService = $deliveryService;
    }

    /**
     * @param ShippingScheduled $event
     * @author k.yamamoto@balocco.info
     */
    public function handle(ShippingScheduled $event)
    {
        /** @var Shipping $shipping */
        $shipping = $event->getOrder()->shipping;
        //出荷予定日
        $shipping->scheduled_shipping_date = Carbon::now();
        //到着予定日
        $shipping->estimated_arrival_date = $this->estimatedArrivalDate($shipping);
        //更新日時を記録しない
        $shipping->timestamps = false;
        //保存
        $shipping->save();
    }

    /**
     * @param Shipping $shipping
     * @return Carbon
     * @author k.yamamoto@balocco.info
     */
    protected function estimatedArrivalDate(Shipping $shipping):Carbon
    {
        //今日発送の場合の到着予定日を計算
        $estimatedArrivalDate = $this->deliveryService->getEstimatedArrivalDate(Carbon::now(), $shipping->prefecture_id,
            $shipping->delivery_id);

        //お届け希望がNULLの場合、今日発送の到著予定日を返す
        if (is_null($shipping->requested_delivery_date)) {
            return $estimatedArrivalDate;
        }

        //今日発送の場合の到着予定日が、希望日よりも早い場合 → 希望日に届けることが可能なので、希望日に届ける。
        if ($estimatedArrivalDate < $shipping->requested_delivery_date) {
            return $shipping->requested_delivery_date;
        }

        //今日発送の場合の到着予定日が、希望日よりも遅い場合。希望日に届けられない。
        return $estimatedArrivalDate;
    }
}
