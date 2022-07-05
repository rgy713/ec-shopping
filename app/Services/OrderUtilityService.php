<?php
/**
 * Created by PhpStorm.
 * User: rgy
 * Date: 5/18/2019
 * Time: 5:49 PM
 */

namespace App\Services;


use App\Models\Customer;
use App\Models\CustomerPairRelationship;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OrderUtilityService
{
    public function getToImportReservation()
    {
        $data = app(Order::class)
            ->select("orders.id", "orders.name01", "orders.name02", "orders.created_at", "shippings.requested_delivery_date")
            ->join('shippings', 'shippings.order_id', 'orders.id')
            ->whereIn("orders.order_status_id",[1,9])
            ->whereDate('shippings.requested_delivery_date', ">=" , Carbon::today()->addDays(6))
            ->get();
        return $data;
    }

    public function getToNewReservation()
    {
        $data = app(Order::class)
            ->select("orders.id", "orders.name01", "orders.name02", "orders.created_at", "shippings.requested_delivery_date")
            ->join('shippings', 'shippings.order_id', 'orders.id')
            ->whereIn("orders.order_status_id",[15])
            ->whereDate('shippings.requested_delivery_date', "<=" , Carbon::today()->addDays(5))
            ->whereDate('shippings.requested_delivery_date', ">=" , Carbon::today())
            ->get();
        return $data;
    }

    public function changeOrderStatus()
    {
        $toImport = $this->getToImportReservation();
        foreach ($toImport as $order){
            $order->order_status_id = 15;
            $order->save();
        }
        $toNew = $this->getToNewReservation();
        foreach ($toNew as $order){
            $order->order_status_id = 1;
            $order->save();
        }
    }

    public function checkNewReservation()
    {
        $count = app(Order::class)
            ->whereIn("order_status_id",[1,9])
            ->count();
        return $count;
    }

    public function getDuplicates()
    {
        $relationship_ids = DB::table("customer_pair_relationships")
            ->select("customer_pair_relationships.id", "orders.id as order_id")
            ->join('orders', function ($join){
                $join->on(function($query){
                    $query->on('orders.customer_id', '=', 'customer_pair_relationships.customer_id_a')
                        ->orOn('orders.customer_id', '=', 'customer_pair_relationships.customer_id_b');
                });
            })
            ->whereIn('customer_pair_relationships.customer_pair_relationship_type_id', [1,2,3])
            ->whereIn('orders.order_status_id', [1,9])
            ->get();

        $list = [];
        foreach($relationship_ids as $one){
            $relationship = app(CustomerPairRelationship::class)->find($one->id);
            $relationship->order = app(Order::class)->find($one->order_id);
            $list[]=$relationship;
        }
        return $list;
    }

    public function mergeCustomer($params)
    {
        $customer_pair_relationship = app(CustomerPairRelationship::class)->find($params["id"]);
        $customerA = app(Customer::class)->find($customer_pair_relationship->customer_id_a);
        $customerB = app(Customer::class)->find($customer_pair_relationship->customer_id_b);
        if($params["merge_type"] == "A"){
            app(MultipleAccountsService::class)->mergePairOfCustomers($customer_pair_relationship->customer_id_b, $customer_pair_relationship->customer_id_a, $customer_pair_relationship);
        }
        elseif($params["merge_type"] == "B"){
            app(MultipleAccountsService::class)->mergePairOfCustomers($customer_pair_relationship->customer_id_b, $customer_pair_relationship->customer_id_a, $customer_pair_relationship);
        }
        elseif($params["merge_type"] == "N")
        {
            $customer_pair_relationship->customer_pair_relationship_type_id = 102;
            $customer_pair_relationship->save();

            $this->subAccountWarningFlag($customerA);
            $this->subAccountWarningFlag($customerB);
        }
    }

    public function subAccountWarningFlag($customer)
    {
        $count = app(CustomerPairRelationship::class)
            ->where(function ($q) use ($customer) {
                $q->orWhere("customer_id_a", $customer->id);
                $q->orWhere("customer_id_b", $customer->id);
            })
            ->whereIn("customer_pair_relationship_type_id",[1,2,3])
            ->count();
        if($count==0){
            $customer->sub_account_warning_flag = false;
            $customer->save();
        }
    }

    public function paymentCancelOrders()
    {
        $orders = app(Order::class)
            ->whereIn("order_status_id",[7,8])
            ->get();
        return $orders;
    }

    public function applyCancel()
    {
        $orders = app(Order::class)
            ->whereIn("order_status_id",[7,8])
            ->get();

        foreach($orders as $order){
            $order->order_status_id = 16;
            $order->save();
        }
    }
}