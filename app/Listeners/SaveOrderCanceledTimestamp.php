<?php

namespace App\Listeners;

use App\Events\OrderCanceled;
use Carbon\Carbon;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * 受注キャンセル日時を記録する
 * Class SaveOrderCanceledTimestamp
 * @package App\Listeners
 * @author k.yamamoto@balocco.info
 */
class SaveOrderCanceledTimestamp
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
     * @param OrderCanceled $event
     * @author k.yamamoto@balocco.info
     */
    public function handle(OrderCanceled $event)
    {
        $order = $event->getOrder();
        $order->canceled_timestamp = Carbon::now();
        //キャンセル日時記録時はupdated_atは更新しない
        $order->timestamps = false;
        //保存
        $order->save();
    }
}
