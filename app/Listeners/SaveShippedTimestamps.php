<?php

namespace App\Listeners;

use App\Events\OrderShipped;
use App\Models\Shipping;
use Carbon\Carbon;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * 出荷日時（管理画面上で「発送済み」に変更した日時）を記録する。
 * Class SaveShippedTimestamps
 * @package App\Listeners
 * @author k.yamamoto@balocco.info
 */
class SaveShippedTimestamps
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
     * TODO:実装
     * @param OrderShipped $event
     * @author k.yamamoto@balocco.info
     */
    public function handle(OrderShipped $event)
    {
        /** @var Shipping $shipping */
        $shipping = $event->getOrder()->shipping;
        //出荷日時の記録
        $shipping->shipped_timestamp = Carbon::now();
        //更新日時は保存しない
        $shipping->timestamps = false;
        //保存
        $shipping->save();

    }
}
