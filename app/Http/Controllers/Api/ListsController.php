<?php


namespace App\Http\Controllers\Api;

use App\Common\KeyValueLists\DeliveryList;
use App\Common\KeyValueLists\DeliveryRequestDateList;
use App\Common\KeyValueLists\PaymentList;
use App\Common\KeyValueLists\PrefectureList;
use Illuminate\Support\Facades\Log;

class ListsController
{

    public function prefecture()
    {
        $list = new PrefectureList();
        return response($list->toArray());
    }

    public function paymentList($order_status_id)
    {
        $paymentList = app(PaymentList::class);
        $paymentList = $paymentList->getKeyValueListWithOrderStatusId($order_status_id);

        return response($paymentList);
    }

    public function deliveryTimeList($delivery_id)
    {
        $deliveryList = app(DeliveryList::class);
        $deliveryTimeList = $deliveryList->getDeliveryTimeList($delivery_id, false);

        return response($deliveryTimeList);
    }

    public function deliveryRequestDateListWithLeadTime($delivery_id, $prefecture_id)
    {
        $deliveryRequestDateList = app(DeliveryRequestDateList::class);
        $deliveryRequestDateListWithLeadTime = $deliveryRequestDateList->getWithLeadTime($prefecture_id, $delivery_id);

        return response($deliveryRequestDateListWithLeadTime);
    }
}