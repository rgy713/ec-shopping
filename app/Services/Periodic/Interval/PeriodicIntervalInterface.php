<?php


namespace App\Services\Periodic\Interval;


use Carbon\Carbon;

/**
 * Interface PeriodicIntervalInterface
 * @package App\Services\Periodic\Interval
 */
interface PeriodicIntervalInterface
{
    /**
     * $baseDateから起算した次回お届け予定日を返す
     * @param Carbon $baseDate
     * @return Carbon
     * @author k.yamamoto@balocco.info
     */
    public function nextDeliveryDate(Carbon $baseDate);
}