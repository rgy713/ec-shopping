<?php
/**
 * Created by PhpStorm.
 * User: rgy
 * Date: 5/11/2019
 * Time: 12:20 AM
 */

namespace App\Common;
use App\Events\Interfaces\GetOrder;
use App\Models\Order;

class SendMailImplementsGetOrder implements GetOrder
{
    /**
     * TestEvent constructor.
     */
    public function __construct(Order $order)
    {
        $this->order=$order;
    }


    /**
     * @inheritDoc
     */
    public function getOrder(): Order
    {
        return $this->order;
    }

}