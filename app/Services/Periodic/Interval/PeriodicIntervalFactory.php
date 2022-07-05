<?php


namespace App\Services\Periodic\Interval;

/**
 * Class PeriodicIntervalFactory
 * @package App\Services\Periodic\Interval
 * @author k.yamamoto@balocco.info
 */
class PeriodicIntervalFactory
{
    /**
     * @param $periodicIntervalTypeId
     * @param $parameters
     * @return PeriodicIntervalType1|PeriodicIntervalType2
     * @author k.yamamoto@balocco.info
     */
    public function create($periodicIntervalTypeId,$parameters){
        switch ($periodicIntervalTypeId){
            case 1 :
                return new PeriodicIntervalType1($parameters);
                break;
            case 2 :
                return new PeriodicIntervalType2($parameters);
                break;

        }
    }
}