<?php

namespace App\Services;

use App\Models\Exports\AccountingExport;
use App\Models\Exports\SearchResultExport;
use App\Models\Exports\PeridocCountExport;
use App\Models\Masters\ItemLineup;
use Illuminate\Support\Facades\DB;

class SummaryService
{
    public function accountingSummary($accounting_type, $term_from, $term_to)
    {
        $query = $this->accountingSummaryQuery($accounting_type, $term_from, $term_to);

        if ($accounting_type == 1 || $accounting_type == 2) {
            $query->select(DB::raw("count(id) as total_count, 
                sum(catalog_price_subtotal + catalog_price_subtotal_tax) as total_total,
                sum(subtotal + subtotal_tax) as net_total,
                sum(delivery_fee + delivery_fee_tax) as delivery_total,
                count(id) filter (where order_count_of_customer = 1) as first_count,
                sum(case when order_count_of_customer = 1 then catalog_price_subtotal + catalog_price_subtotal_tax - subtotal - subtotal_tax else 0 end) as first_total,
                count(id) filter (where order_count_of_customer >= 2 and periodic_order_id is null) as peat_count,
                sum(case when order_count_of_customer >= 2 and periodic_order_id is null then catalog_price_subtotal + catalog_price_subtotal_tax - subtotal - subtotal_tax else 0 end) as peat_total,
                count(id) filter (where order_count_of_customer >= 2 and periodic_order_id is not null) as periodic_count,
                sum(case when order_count_of_customer >= 2 and periodic_order_id is not null then catalog_price_subtotal + catalog_price_subtotal_tax - subtotal - subtotal_tax else 0 end) as periodic_total,
                sum(discount) as special_total"));

            return $query->first();
        }
        else if ($accounting_type == 3) {
            $query->select(DB::raw("count(id) as total_count, 
                sum(catalog_price_subtotal + catalog_price_subtotal_tax) as total_total,
                sum(case when order_count_of_customer = 1 then catalog_price_subtotal + catalog_price_subtotal_tax else 0 end) as new_total,
                sum(case when order_count_of_customer >= 2 then catalog_price_subtotal + catalog_price_subtotal_tax else 0 end) as peat_total"));

            return $query->first();
        }
        else if ($accounting_type == 4 || $accounting_type == 5) {
            $query->select('stock_keeping_units.name as name', 'stock_keeping_units.unit_name as unit_name', DB::raw("count(*) as count, sum(order_details.quantity * order_details.volume) as total"))
                ->groupby('stock_keeping_units.id', 'stock_keeping_units.name', 'stock_keeping_units.unit_name')
                ->orderby('stock_keeping_units.id');
        }

        return $query->get();
    }

    public function accountingDownloadCsv($accounting_type, $term_from, $term_to)
    {
        $query = $this->accountingSummaryQuery($accounting_type, $term_from, $term_to);

        $accountingExport = app(AccountingExport::class, ['accounting_type'=>$accounting_type, 'query'=>$query]);
        return $accountingExport->download();
    }

    public function accountingSummaryQuery($accounting_type, $term_from, $term_to)
    {
        $query= DB::table('orders')->where('orders.is_wholesale', false)->whereNull('orders.deleted_at');

        if ($accounting_type == 1 || $accounting_type == 3 || $accounting_type == 4 || $accounting_type == 5) {
            if (!is_null($term_from) && !is_null($term_to)) {
                $query->whereBetween('orders.sales_timestamp', [$term_from, $term_to]);
                $query->where(function($query1) use ($term_from, $term_to) {
                    $query1->whereNotBetween('orders.canceled_timestamp', [$term_from, $term_to])
                         ->orWhereRaw('orders.canceled_timestamp is null');
                });
            }

            if ($accounting_type == 4 || $accounting_type == 5) {
                $query->join('order_details', 'order_details.order_id', 'orders.id')
                    ->join('products', 'products.id', 'order_details.product_id')
                    ->join('products_skus', 'products_skus.product_id', 'order_details.product_id')
                    ->join('stock_keeping_units', 'stock_keeping_units.id', 'products_skus.stock_keeping_unit_id')
                    ->where('products.undelivered_summary_classification_id', $accounting_type == 4 ? 2 : 3);
            }
        }
        else if ($accounting_type == 2) {
            if (!is_null($term_from) && !is_null($term_to)) {
                $query->whereBetween('orders.canceled_timestamp', [$term_from, $term_to]);
            }
        }

        return $query;
    }

    public function marketingSummary($marketing_type, $summary_type, $term_from, $term_to)
    {
        $query = $this->marketingSummaryQuery($marketing_type, $summary_type, $term_from, $term_to);

        if ($marketing_type == 1) {
            $query->select(DB::raw("sum(orders.catalog_price_subtotal + orders.catalog_price_subtotal_tax) as total_total,
                sum(orders.delivery_fee + orders.delivery_fee_tax) as delivery_total,
                sum(case when orders.order_count_of_customer = 1 then orders.catalog_price_subtotal + orders.catalog_price_subtotal_tax else 0 end) as new_total,
                count(distinct customers.id) filter (where orders.order_count_of_customer = 1) as new_customer_count,
                count(orders.id) filter (where orders.order_count_of_customer = 1) as new_count,
                sum(case when orders.order_count_of_customer >= 2 then orders.catalog_price_subtotal + orders.catalog_price_subtotal_tax else 0 end) as peat_total,
                count(distinct customers.id) filter (where orders.order_count_of_customer >= 2) as peat_customer_count,
                count(orders.id) filter (where orders.order_count_of_customer >= 2) as peat_count"))
                ->join('customers', 'customers.id', 'orders.customer_id');

            return $query->first();
        }
        else if ($marketing_type == 2) {
            $query->join('order_details', 'order_details.order_id', 'orders.id')
                ->join('products', 'products.id', 'order_details.product_id')
                ->where('products.undelivered_summary_classification_id', 1);

            $itemLineup = DB::table('item_lineups')->select('id')->where([['id', '<>', 2], ['id', '<>', 6]])->get()->toArray();
            $is_lp_list = [0, 1];
            $is_periodic_list = [0, 1];
            $volume_list = ['1', '2', '3'];

            foreach ($itemLineup as $item) {
                $item_lineup_id = $item->id;

                foreach ($is_lp_list as $is_lp) {
                    foreach ($is_periodic_list as $is_periodic) {
                        foreach ($volume_list as $volume) {
                            $count_index = implode('_', [$item_lineup_id, $is_lp, $is_periodic, $volume]);
                            $cond = implode(' and ',
                                [
                                    'products.item_lineup_id=' . $item_lineup_id,
                                    'products.is_lp=' . ($is_lp == 1 ? 'true' : 'false'),
                                    'products.is_periodic=' . ($is_periodic == 1 ? 'true' : 'false'),
                                    'products.volume=' . $volume
                                ]);
                            $cond = sprintf('sum(case when %s then order_details.quantity else 0 end) as count_%s', $cond, $count_index);

                            $query->addSelect(DB::raw($cond));
                        }
                    }
                }

                $cond = sprintf('sum(case when products.item_lineup_id=%s then order_details.quantity else 0 end) as count_%s_sum', $item_lineup_id, $item_lineup_id);
                $query->addSelect(DB::raw($cond));

                $cond = sprintf('sum(case when products.item_lineup_id=%s then order_details.quantity * order_details.volume else 0 end) as count_%s_sum_total', $item_lineup_id, $item_lineup_id);
                $query->addSelect(DB::raw($cond));
            }

            return $query->first();
        }
        else if ($marketing_type == 3) {
            $query->join('order_details', 'order_details.order_id', 'orders.id')
                ->join('products', 'products.id', 'order_details.product_id');

            $itemLineup = ItemLineup::select('id')->where([['id', '<>', 2], ['id', '<>', 6]])->get()->toArray();

            foreach ($itemLineup as $item) {
                $item_lineup_id = $item['id'];

                $cond = sprintf('sum(case when products.item_lineup_id=%s and products.undelivered_summary_classification_id=2 then order_details.quantity * order_details.volume else 0 end) as sum_2_%s', $item_lineup_id, $item_lineup_id);
                $query->addSelect(DB::raw($cond));

                $cond = sprintf('sum(case when products.item_lineup_id=%s and products.undelivered_summary_classification_id=3 then order_details.quantity * order_details.volume else 0 end) as sum_3_%s', $item_lineup_id, $item_lineup_id);
                $query->addSelect(DB::raw($cond));
            }

            return $query->first();
        }
        else if ($marketing_type == 4 || $marketing_type == 5) {
            $query->select('orders.customer_id as customer_id', DB::raw('least(max(coalesce(orders.order_count_of_customer_without_cancel, 0)), 10) as order_count'))
                ->groupby('orders.customer_id');

            $total = $query->get()->count();

            $query_counts = DB::table(DB::raw(sprintf('(%s) as results', $query->toSql())))
                ->select(DB::raw('results.order_count as order_count, count(results.order_count) as customer_count'))
                ->groupby('results.order_count')
                ->setBindings($query->getBindings());

            $query_counts = DB::table(DB::raw('generate_series(0, 10) as counts'))
                ->select('counts', 'count_results.customer_count as customer_count')
                ->leftJoin(DB::raw(sprintf('(%s) as count_results', $query_counts->toSql())), 'count_results.order_count', 'counts')
                ->setBindings($query_counts->getBindings());

            $query_counts = $query_counts->get()->toArray();
            array_unshift($query_counts, ['customer_count'=>$total]);

            return $query_counts;
        }

        return $query->get();
    }

    public function marketingDownloadCsv($marketing_type, $term_from, $term_to)
    {
        $query = DB::table("marketing_summary_batch_logs");
        if (!is_null($term_from) && !is_null($term_to)) {
            $query->whereBetween('marketing_summary_batch_logs.created_at', [$term_from, $term_to]);
        }

        $searchResultExport = app(SearchResultExport::class, ['csv_type_id'=>15, 'query'=>$query]);
        return $searchResultExport->download();
    }

    public function marketingSummaryQuery($marketing_type, $summary_type, $term_from, $term_to)
    {
        $query= DB::table('orders')->where('orders.is_wholesale', false)->whereNull('orders.deleted_at');

        if ($marketing_type != 5) {
            if (!is_null($term_from) && !is_null($term_to)) {
                if ($summary_type == 1) {
                    $query->whereBetween('orders.sales_timestamp', [$term_from, $term_to]);
                    $query->where(function($query1) use ($term_from, $term_to) {
                        $query1->whereNotBetween('orders.canceled_timestamp', [$term_from, $term_to])
                            ->orWhereRaw('orders.canceled_timestamp is null');
                    });
                }
                else if ($summary_type == 2) {
                    $query->whereBetween('orders.canceled_timestamp', [$term_from, $term_to]);
                }

            }
        }

        return $query;
    }

    public function wholesaleSummary1($term_from, $term_to)
    {
        $query = DB::table('orders')->whereNull('orders.deleted_at')
            ->select(DB::raw('sum(orders.subtotal + orders.subtotal_tax) as total, count(orders.id) as count'))
            ->join('customers', 'customers.id', 'orders.customer_id')
            ->whereNotIn('orders.order_status_id', [16,17,18,19,20,21])
            ->where('orders.is_wholesale', true);

        if (!is_null($term_from) && !is_null($term_to)) {
            $query->whereBetween('orders.sales_timestamp', [$term_from, $term_to]);
        }

        $orders_sum = $query->get()->first();

        $query->addSelect('orders.customer_id', 'customers.name01', 'customers.name02')->groupBy('orders.customer_id', 'customers.name01', 'customers.name02');
        $orders = $query->get();

        return [
            "summary"=>$orders,
            "sum"=>$orders_sum
        ];
    }

    public function wholesaleSummary2($term_from, $term_to)
    {
        $orders = DB::table('orders')->whereNull('orders.deleted_at')
            ->select('stock_keeping_units.id', 'stock_keeping_units.name', DB::raw('count(stock_keeping_units.id) as count'))
            ->join('order_details', 'order_details.order_id', 'orders.id')
            ->join('products_skus', 'products_skus.product_id', 'order_details.product_id')
            ->join('stock_keeping_units', 'stock_keeping_units.id', 'products_skus.stock_keeping_unit_id')
            ->whereNotIn('orders.order_status_id', [16,17,18,19,20,21])
            ->where('orders.is_wholesale', true);

        if (!is_null($term_from) && !is_null($term_to)) {
            $orders->whereBetween('orders.sales_timestamp', [$term_from, $term_to]);
        }

        return $orders->groupBy('stock_keeping_units.id')
            ->get();
    }

    public function wholesaleDownloadCSV($customer_id, $term_from, $term_to)
    {
        $query = DB::table('orders')->whereNull('orders.deleted_at')
            ->join('customers', 'customers.id', 'orders.customer_id')
            ->join('shippings', 'orders.id', 'shippings.order_id')
            ->leftJoin('order_bundle_shippings', 'orders.id', 'order_bundle_shippings.order_id')
            ->where('orders.customer_id', $customer_id)
            ->whereNotIn('orders.order_status_id', [16,17,18,19,20,21])
            ->where('orders.is_wholesale', true);

        if (!is_null($term_from) && !is_null($term_to)) {
            $query->whereBetween('orders.sales_timestamp', [$term_from, $term_to]);
        }

        $searchResultExport = app(SearchResultExport::class, ['csv_type_id'=>7, 'query'=>$query]);
        return $searchResultExport->download();
    }

    public function ageSummary($term_from, $term_to)
    {
        $query = DB::table('orders')->whereNull('orders.deleted_at')
            ->select(DB::raw('sum(orders.subtotal + orders.subtotal_tax) as total, count(orders.id) as count'))
            ->join('customers', 'customers.id', 'orders.customer_id')
            ->whereNotIn('orders.order_status_id', [16,17,18,19,20,21])
            ->where('orders.is_wholesale', false)
            ->whereNotNull('customers.birthday');

        if (!is_null($term_from) && !is_null($term_to)) {
            $query->whereBetween('orders.sales_timestamp', [$term_from, $term_to]);
        }

        $orders_sum = $query->get()->first();

        $query->addSelect(DB::raw('floor(EXTRACT( year FROM age(CURRENT_DATE, customers.birthday)) / 10) * 10 as customer_age'))->groupBy('customer_age');
        $orders = $query->get();

        return [
            "summary"=>$orders,
            "sum"=>$orders_sum
        ];
    }

    public function ageDownloadCSV($age, $term_from, $term_to)
    {
        $query = DB::table('orders')->whereNull('orders.deleted_at')
            ->join('customers', 'customers.id', 'orders.customer_id')
            ->join('shippings', 'orders.id', 'shippings.order_id')
            ->leftJoin('order_bundle_shippings', 'orders.id', 'order_bundle_shippings.order_id')
            ->where(DB::raw('floor(EXTRACT( year FROM age(CURRENT_DATE, customers.birthday)) / 10) * 10'), $age)
            ->whereNotIn('orders.order_status_id', [16,17,18,19,20,21])
            ->where('orders.is_wholesale', false)
            ->whereNotNull('customers.birthday');

        if (!is_null($term_from) && !is_null($term_to)) {
            $query->whereBetween('orders.sales_timestamp', [$term_from, $term_to]);
        }

        $searchResultExport = app(SearchResultExport::class, ['csv_type_id'=>8, 'query'=>$query]);
        return $searchResultExport->download();
    }

    public function paymentSummary($term_from, $term_to)
    {
        $query = DB::table('orders')->whereNull('orders.deleted_at')
            ->select(DB::raw('sum(subtotal + subtotal_tax) as total, count(id) as count'))
            ->whereNotIn('order_status_id', [16,17,18,19,20,21])
            ->where('is_wholesale', false);

        if (!is_null($term_from) && !is_null($term_to)) {
            $query->whereBetween('sales_timestamp', [$term_from, $term_to]);
        }

        $orders_sum = $query->get()->first();

        $query->addSelect('payment_method_id', 'payment_method_name')->groupBy('payment_method_id', 'payment_method_name');
        $orders = $query->get();

        return [
            "summary"=>$orders,
            "sum"=>$orders_sum
        ];
    }

    public function paymentDownloadCSV($payment_method_id, $term_from, $term_to)
    {
        $query = DB::table('orders')->whereNull('orders.deleted_at')
            ->join('customers', 'customers.id', 'orders.customer_id')
            ->join('shippings', 'orders.id', 'shippings.order_id')
            ->leftJoin('order_bundle_shippings', 'orders.id', 'order_bundle_shippings.order_id')
            ->where('orders.payment_method_id', $payment_method_id)
            ->whereNotIn('orders.order_status_id', [16,17,18,19,20,21])
            ->where('orders.is_wholesale', false);

        if (!is_null($term_from) && !is_null($term_to)) {
            $query->whereBetween('orders.sales_timestamp', [$term_from, $term_to]);
        }

        $searchResultExport = app(SearchResultExport::class, ['csv_type_id'=>9, 'query'=>$query]);
        return $searchResultExport->download();
    }

    public function periodicCountSummary($item_lineup_id)
    {
        $periodic_orders = $this->periodicCountSummaryQuery();
        if ($item_lineup_id > 0) {
            $periodic_orders->whereRaw(sprintf('products.item_lineup_id = %d', $item_lineup_id));
        }

        $periodic_orders_sum = $periodic_orders->get();

        $periodic_orders->addSelect(DB::raw('least(periodic_orders.periodic_count, 21) as count'))
            ->groupby('count');

        $periodic_counts = DB::table(DB::raw('generate_series(0, 21) as counts'))
            ->select('counts', 'results.active_count as active_count', 'results.stop_count as stop_count')
            ->leftJoin(DB::raw(sprintf('(%s) as results', $periodic_orders->toSql())), 'results.count', 'counts')
            ->get();

        return [
            "summary"=>$periodic_counts,
            "sum"=>$periodic_orders_sum,
        ];
    }

    public function periodicCountSummaryQuery()
    {
        $periodic_orders = DB::table('periodic_order_details')
            ->select(DB::raw('count(*) filter (where periodic_orders.stop_flag = false) as active_count, count(*) filter (where periodic_orders.stop_flag = true) as stop_count'))
            ->join('periodic_orders', 'periodic_orders.id', 'periodic_order_details.periodic_order_id')
            ->join('products', 'products.id', 'periodic_order_details.product_id')
            ->whereNull('periodic_orders.deleted_at')
            ->whereNotNull('periodic_orders.confirmed_timestamp');

        return $periodic_orders;
    }

    public function periodicCountDownloadCsv($export_type, $term_from, $term_to)
    {
        $peridocCountExport = app(PeridocCountExport::class, ['export_type'=>$export_type, 'term_from'=>$term_from, 'term_to'=>$term_to]);
        return $peridocCountExport->download();
    }
}