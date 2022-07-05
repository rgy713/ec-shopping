<?php


namespace App\Services\Periodic\Interval;


use Carbon\Carbon;

/**
 * Class PeriodicIntervalType2
 * @package App\Services\Periodic\Interval
 * @author k.yamamoto@balocco.info
 */
class PeriodicIntervalType2 implements PeriodicIntervalInterface
{
    /**
     * @var 間隔月数を示す数値。1～
     */
    protected $intervalMonth;

    /**
     * @var int 日付を示す数値。1～28。
     */
    protected $intervalDateOfMonth;

    /**
     * PeriodicIntervalType1 constructor.
     */
    public function __construct($parameters)
    {
        $this->intervalMonth=$parameters['interval_month'];
        $this->intervalDateOfMonth=$parameters['interval_date_of_month'];
    }

    /**
     * @param Carbon $baseDate
     * @return Carbon
     * @author k.yamamoto@balocco.info
     */
    public function nextDeliveryDate(Carbon $baseDate)
    {
        return $baseDate->firstOfMonth()->addMonth($this->intervalMonth)->day($this->intervalDateOfMonth);
    }

}