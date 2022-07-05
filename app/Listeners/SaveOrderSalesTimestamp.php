<?php

namespace App\Listeners;

use App\Events\OrderAccounted;
use Carbon\Carbon;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * 売上計上日時を記録する
 * Class SaveOrderSalesTimestamp
 * @package App\Listeners
 * @author k.yamamoto@balocco.info
 */
class SaveOrderSalesTimestamp
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * @param OrderAccounted $event
     * @author k.yamamoto@balocco.info
     */
    public function handle(OrderAccounted $event)
    {
        $order = $event->getOrder();
        $order->sales_timestamp = Carbon::now();
        //売上計上日時記録時はupdated_atは更新しない
        $order->timestamps = false;
        //保存
        $order->save();
    }
}
