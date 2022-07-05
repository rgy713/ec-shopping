<?php

namespace App\Models\Exports;

use App\Models\Order;
use App\Models\Traits\DefaultModelExport;
use Illuminate\Support\Facades\DB;

class OrderSearchResultExport
{
    use DefaultModelExport;

    public function __construct($query, $is_shipping = false)
    {
        $this->query = $query;
        if ($is_shipping == false) {
            $this->csv_type_id = 2;
            $this->query->join('shippings', 'shippings.order_id', 'orders.id')
                ->join('prefectures', 'prefectures.id', 'orders.prefecture_id')
                ->leftJoin('order_statuses', 'order_statuses.id', 'orders.order_status_id')
                ->whereNotNull('orders.confirmed_timestamp');
        }
        else {
            $this->csv_type_id = 4;

            $bundled_order_detail = DB::table('order_details')->select(
                'order_details.id',
                DB::Raw('COALESCE (order_bundle_shippings.parent_order_id,order_details.order_id) as order_id'),
                'order_details.product_id',
                'order_details.product_name',
                'order_details.product_code',
                'order_details.price',
                'order_details.tax',
                'order_details.catalog_price',
                'order_details.catalog_price_tax',
                'order_details.quantity',
                'order_details.volume',
                'order_details.order_id as original_order_id'
            )
                ->leftJoin('order_bundle_shippings', 'order_bundle_shippings.order_id', 'order_details.order_id');

            $bundled_order_summary = DB::table('orders')->select(
                DB::Raw('COALESCE(order_bundle_shippings.parent_order_id,orders.id) as order_id,
                sum(orders.subtotal) AS subtotal,
                sum(orders.subtotal_tax) AS subtotal_tax,
                sum(orders.discount) AS discount,
                sum(orders.delivery_fee) AS delivery_fee,
                sum(orders.delivery_fee_tax) AS delivery_fee_tax,
                sum(orders.payment_method_fee) AS payment_method_fee,
                sum(orders.payment_method_fee_tax) AS payment_method_fee_tax,
                sum(orders.payment_total) AS payment_total,
                sum(orders.payment_total_tax) AS payment_total_tax,
                count(orders.id) AS num_bundled')
            )
                ->leftJoin('order_bundle_shippings', 'order_bundle_shippings.order_id', 'orders.id')
                ->whereNull('orders.deleted_at')
                ->whereNotNull('orders.confirmed_timestamp')
                ->groupby(DB::Raw('COALESCE(order_bundle_shippings.parent_order_id,orders.id)'));

            $bundled_order_ids = DB::table('order_bundle_shippings')->select('order_id as bundled_order_id')->whereNotNull('order_id');

            $this->query->join(DB::Raw(sprintf('(%s) as bundled_order_detail', $bundled_order_detail->toSql())), 'bundled_order_detail.order_id', 'orders.id')
                ->join('products', 'products.id', 'bundled_order_detail.product_id')
                ->join(DB::Raw(sprintf('(%s) as bundled_order_summary', $bundled_order_summary->toSql())), 'bundled_order_summary.order_id', 'orders.id')
                ->join('prefectures as order_prefectures', 'order_prefectures.id', 'orders.prefecture_id')
                ->join('shippings', 'shippings.order_id', 'orders.id')
                ->join('prefectures as shipping_prefectures', 'shipping_prefectures.id', 'shippings.prefecture_id')
                ->join('customers', 'customers.id', 'orders.customer_id')
                ->join('orders as original_orders', 'original_orders.id', 'bundled_order_detail.original_order_id')
                ->leftJoin('periodic_orders as original_periodic_orders', 'original_periodic_orders.id', 'original_orders.periodic_order_id')
                ->whereNotIn('orders.id', $bundled_order_ids)
                ->whereNotNull('orders.confirmed_timestamp');
        }

    }
}