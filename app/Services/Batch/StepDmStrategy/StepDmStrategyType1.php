<?php
/**
 * Created by PhpStorm.
 * User: rgy
 * Date: 5/25/2019
 * Time: 10:27 AM
 */

namespace App\Services\Batch\StepDmStrategy;


class StepDmStrategyType1
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
        if($this->order->periodic_count == $this->stepdm_setting->req_periodic_count)
            return true;
        else
            return false;
    }
}