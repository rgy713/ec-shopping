<?php
/**
 * Created by PhpStorm.
 * User: rgy
 * Date: 5/25/2019
 * Time: 10:33 AM
 */

namespace App\Services\Batch\StepDmStrategy;


class StepDmStrategyType2
{
    protected $order;
    protected $stepdm_setting;

    function __construct($order, $stepdm_setting)
    {
        $this->order = $order;
        $this->stepdm_setting = $stepdm_setting;
    }

    function addCondition()
    {
        $count = $this->order->customer->periodicOrders()
            ->whereNotNull("confirmed_timestamp")
            ->whereHas("details", function($q){
                $q->where('product_code','like','%CGC-TTT%');
            })
            ->count();
        if($count>0)
            return false;
        else
            return true;
    }
}