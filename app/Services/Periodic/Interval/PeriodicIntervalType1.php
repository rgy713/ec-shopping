<?php


namespace App\Services\Periodic\Interval;

use Carbon\Carbon;

/**
 * Class PeriodicIntervalType1
 * @package App\Services\Periodic\Interval
 * @author k.yamamoto@balocco.info
 */
class PeriodicIntervalType1 implements PeriodicIntervalInterface
{
    /**
     * @var integer
     */
    protected $intervalDays;
    /**
     * PeriodicIntervalType1 constructor.
     */
    public function __construct($parameters)
    {
        $this->intervalDays=$parameters['interval_days'];
    }

    /**
     * @param Carbon $baseDate
     * @return Carbon
     * @author k.yamamoto@balocco.info
     */
    public function nextDeliveryDate(Carbon $baseDate)
    {
        return $baseDate->addDays($this->intervalDays);
    }

}