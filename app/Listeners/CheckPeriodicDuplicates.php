<?php

namespace App\Listeners;

use App\Events\PeriodicOrderRegistered;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * 定期の重複チェックを行う
 * Class CheckPeriodicDuplicates
 * @package App\Listeners
 * @author k.yamamoto@balocco.info
 */
class CheckPeriodicDuplicates
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
     * Handle the event.
     * TODO:実装
     * @param  PeriodicOrderRegistered  $event
     * @return void
     */
    public function handle(PeriodicOrderRegistered $event)
    {
        //定期の重複チェックを行う。
        //条件：購入者が同じ顧客IDである
        //条件：商品構成が、同じラインナップである

        //結果1.periodic_order_pair_relationshipsテーブルに、組み合わせを保存
        //結果2.periodic_order_pair_relationshipsのレコード状況から判断し、結果をduplication_warning_flagに保存

    }
}
