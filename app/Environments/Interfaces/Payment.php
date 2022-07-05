<?php


namespace App\Environments\Interfaces;

use App\Models\Order;
use App\Models\PeriodicOrder;

interface Payment
{
    /**
     * 共通処理 #2010 定期受注決済処理
     * @param Order $order
     * @param PeriodicOrder $periodicOrder
     * @return boolean
     */
    public function execPeriodicOrder(Order $order,PeriodicOrder $periodicOrder);
}