<?php
/**
 * Created by PhpStorm.
 * User: rgy
 * Date: 5/25/2019
 * Time: 4:27 PM
 */

namespace App\Services\Batch;


use App\Models\OrderBundleShipping;
use App\Services\OrderService;
use Illuminate\Support\Facades\DB;

class BundleShippingService
{
    public function run()
    {
        $exception_count = 0;

        $results = DB::table("orders")
            ->select(
                DB::raw("(shippings.prefecture_id || shippings.address01 || shippings.address02)  as address"),
                DB::raw("array_to_json(array_agg(orders.id)) AS array_order_id")
            )
            ->join("shippings", "orders.id", "=", "shippings.order_id")
            ->whereNull("orders.deleted_at")
            ->whereNotNull("orders.confirmed_timestamp")
            ->whereIn("orders.order_status_id", app(OrderService::class)->getBundleTargetStatuses())
            ->whereRaw("orders.id NOT IN (SELECT order_id FROM order_bundle_shippings)")
            ->groupBy(
                "orders.customer_id",
                DB::raw("(shippings.prefecture_id || shippings.address01 || shippings.address02)"),
                "shippings.zipcode",
                "orders.payment_method_id"
            )
            ->having(DB::raw("count(orders.id)"), ">", 1)
            ->get();

        if(count($results) == 0){
            return -1;
        }

        foreach ($results as $result){
            $order_ids = json_decode($result->array_order_id);
            if(count($order_ids)==0)
                continue;

            $add_order_ids = app(OrderBundleShipping::class)
                ->whereIn("parent_order_id", array_map("intval", $order_ids))
                ->get()->pluck("order_id")->toArray();

            $order_ids = array_merge($order_ids, $add_order_ids);

            $max_order_id = max($order_ids);

            DB::beginTransaction();

            try{
                app(OrderBundleShipping::class)
                    ->whereIn("order_id", array_map("intval", $order_ids))
                    ->delete();
                app(OrderBundleShipping::class)
                    ->whereIn("parent_order_id", array_map("intval", $order_ids))
                    ->delete();

                foreach ($order_ids as $order_id){
                    if($order_id == $max_order_id)
                        continue;

                    $order_bundle_shipping = new OrderBundleShipping();
                    $order_bundle_shipping->parent_order_id = $max_order_id;
                    $order_bundle_shipping->order_id = $order_id;
                    $order_bundle_shipping->save();
                }
            }
            catch (\Exception $e){
                $exception_count += 1;
                DB::rollBack();
            }

            DB::commit();
        }

        return $exception_count;
    }
}