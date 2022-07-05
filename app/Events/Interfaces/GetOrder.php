<?php


namespace App\Events\Interfaces;


use App\Models\Order;

interface GetOrder
{
    /**
     * @return Order
     * @author k.yamamoto@balocco.info
     */
    public function getOrder(): Order;
}