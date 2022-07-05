<?php
/**
 * Created by PhpStorm.
 * User: rgy
 * Date: 5/12/2019
 * Time: 4:10 PM
 */

namespace App\Services;


use App\Events\OrderStatusUpdated;
use App\Models\Order;
use App\Models\OrderBundleShipping;
use App\Models\Shipping;

class DeliverySlipService
{
    public function import($import_data)
    {
        foreach ($import_data as $data){
            $order_id = $data["order_id"];
            $order = app(Order::class)->find($order_id);
            if(in_array($order->order_status_id, [11, 14 ,4 ,12])) {
                $shipping = $order->shipping;
                $shipping->delivery_slip_num = $data["delivery_slip_num"];
                $shipping->save();

                $order->order_status_id = 5;
                $order->save();

                event(new OrderStatusUpdated($order->order_status_id, $order));
            }

            $orderBundleShipping = app(OrderBundleShipping::class)->where("parent_order_id", $order_id)->first();
            if($orderBundleShipping){
                $order = $orderBundleShipping->order;
                if(in_array($order->order_status_id, [11, 14 ,4 ,12])) {
                    $shipping = $order->shipping;
                    $shipping->delivery_slip_num = $data["delivery_slip_num"];
                    $shipping->save();

                    $order->order_status_id = 5;
                    $order->save();

                    event(new OrderStatusUpdated($order->order_status_id, $order));
                }
            }
        }
    }
}