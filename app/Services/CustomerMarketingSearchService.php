<?php
/**
 * Created by PhpStorm.
 * User: rgy
 * Date: 4/27/2019
 * Time: 6:43 PM
 */

namespace App\Services;


use App\Models\Customer;
use App\Models\Exports\SearchResultExport;
use Illuminate\Support\Facades\DB;

class CustomerMarketingSearchService
{
    protected function searchQuery($params)
    {
        $customers = DB::table('customers');
        $customers->whereNull('customers.deleted_at');
//顧客ID
        $min = $params['customer_id_min'];
        $max = $params['customer_id_max'];
        if (isset($min) && isset($max))
            $customers->whereBetween('customers.id', [$min, $max]);
        elseif (isset($min) && !isset($max))
            $customers->where('customers.id', '>=', $min);
        elseif (!isset($min) && isset($max))
            $customers->where('customers.id', '<=', $max);
//会員状態
        if(isset($params["customer_status"])){
            $customer_status = array_map('intval', $params['customer_status']);
            $customers->whereIn('customers.customer_status_id', $customer_status);
        }
//架電禁止
        if(isset($params["customer_no_phone_call"]) && $params["customer_no_phone_call"] == 1)
            $customers->where('customers.no_phone_call_flag', true);
//メルマガ
        if(isset($params["customer_mail_flag"])){
            $customer_mail_flag = [];
            foreach($params['customer_mail_flag'] as $one){
                $customer_mail_flag[] = $one == 2 ? true: false;
            };
            $customers->whereIn('customers.mail_magazine_flag', $customer_mail_flag);
        }
//DM可否
        if(isset($params["customer_dm_flag"])){
            $customer_dm_flag = [];
            foreach($params['customer_dm_flag'] as $one){
                $customer_dm_flag[] = $one == 1 ? true: false;
            };
            $customers->whereIn('customers.dm_flag', $customer_dm_flag);
        }
//会員登録日
        $from = $params['customer_created_at_from'];
        $to = $params['customer_created_at_to'];
        if (isset($from) && isset($to))
            $customers->whereBetween('customers.created_at', [$from." 00:00:00", $to." 23:59:59"]);
        elseif (isset($from) && !isset($to))
            $customers->where('customers.created_at', '>=', $from." 00:00:00");
        elseif (!isset($from) && isset($to))
            $customers->where('customers.created_at', '<=', $to." 23:59:59");
//会員情報更新日
        $from = $params['customer_updated_at_from'];
        $to = $params['customer_updated_at_to'];
        if (isset($from) && isset($to))
            $customers->whereBetween('customers.updated_at', [$from." 00:00:00", $to." 23:59:59"]);
        elseif (isset($from) && !isset($to))
            $customers->whereDate('customers.updated_at', '>=', $from." 00:00:00");
        elseif (!isset($from) && isset($to))
            $customers->whereDate('customers.updated_at', '<=', $to." 23:59:59");
//都道府県
        if(isset($params["customer_prefecture"])){
            $prefectures = array_map('intval', $params['customer_prefecture']);
            $customers->whereIn('customers.prefecture_id', $prefectures);
        }
//誕生日
        $from = $params['customer_birthday_from'];
        $to = $params['customer_birthday_to'];
        if (isset($from) && isset($to))
            $customers->whereBetween('customers.birthday', [date($from), date($to)]);
        elseif (isset($from) && !isset($to))
            $customers->whereDate('customers.birthday', '>=', date($from));
        elseif (!isset($from) && isset($to))
            $customers->whereDate('customers.birthday', '<=', date($to));
//誕生月
        if(isset($params["customer_birth_month"])){
            $birth_month = array_map('intval', $params['customer_birth_month']);
            $customers->whereIn(DB::raw('EXTRACT(MONTH FROM customers.birthday )'), $birth_month);
        }
//PFM属性
        if(isset($params["customer_pfm"])){
            $pfm_status = array_map('intval', $params['customer_pfm']);
            $customers->whereIn('customers.pfm_status_id', $pfm_status);
        }
//広告番号
        if(isset($params["customer_media_code"])){
            $media_code = array_map('intval', $params['customer_media_code']);
            $customers->whereIn('customers.advertising_media_code', $media_code);
        }

        if(isset($params["customer_item_lineup"])
            || isset($params["customer_sales_target"])
            || isset($params["customer_sales_route"])
            || isset($params["customer_undelivered_summary_classification"])
            || isset($params["customer_marketing_summary_classification"])
            || isset($params["customer_product_volume_list"])
            || isset($params["customer_product_name"])
            || isset($params["customer_product_code"])
            || isset($params["customer_buy_total_min"])
            || isset($params["customer_buy_total_max"])
            || isset($params["customer_buy_times_min"])
            || isset($params["customer_buy_times_max"])
            || isset($params["customer_buy_volume_min"])
            || isset($params["customer_buy_volume_max"])
            || isset($params["customer_last_buy_date_from"])
            || isset($params["customer_last_buy_date_to"])
            || isset($params["customer_withdrawal_date_from"])
            || isset($params["customer_withdrawal_date_to"])
        )
            $customers->leftJoin('orders', 'customers.id', 'orders.customer_id')
                ->leftJoin('order_details', 'orders.id', 'order_details.order_id')
                ->leftJoin('products', 'order_details.product_id', 'products.id');

//ラインナップ
        if(isset($params["customer_item_lineup"])){
            $item_lineup = array_map('intval', $params['customer_item_lineup']);
            $customers->where(function($query) use ($item_lineup) {
                $query->whereNotNull('orders.confirmed_timestamp');
                $query->whereNull('orders.deleted_at');
                $query->whereIn('products.item_lineup_id', $item_lineup);
            });
        }
//対象顧客
        if(isset($params["customer_sales_target"])){
            $sales_target = array_map('intval', $params['customer_sales_target']);
            $customers->where(function($query) use ($sales_target) {
                $query->whereNotNull('orders.confirmed_timestamp');
                $query->whereNull('orders.deleted_at');
                $query->whereIn('products.sales_target_id', $sales_target);
            });
        }
//販売経路
        if(isset($params["customer_sales_route"])){
            $sales_route = array_map('intval', $params['customer_sales_route']);
            $customers->where(function($query) use ($sales_route) {
                $query->whereNotNull('orders.confirmed_timestamp');
                $query->whereNull('orders.deleted_at');
                $query->whereIn('products.sales_route_id', $sales_route);
            });
        }
//未発送集計区分
        if(isset($params["customer_undelivered_summary_classification"])){
            $summary_classification = array_map('intval', $params['customer_undelivered_summary_classification']);
            $customers->where(function($query) use ($summary_classification) {
                $query->whereNotNull('orders.confirmed_timestamp');
                $query->whereNull('orders.deleted_at');
                $query->whereIn('products.undelivered_summary_classification_id', $summary_classification);
            });
        }
//販売属性
        if (isset($params['customer_marketing_summary_classification'])) {
            $marketing_summary_classification_id = array_map('intval', $params['customer_marketing_summary_classification']);
            $customers->where(function($query) use ($marketing_summary_classification_id) {
                $query->whereNotNull('orders.confirmed_timestamp');
                $query->whereNull('orders.deleted_at');
                $index = 0;
                foreach($marketing_summary_classification_id as $marketing_id) {
                    if ($marketing_id == 1) {
                        if ($index == 0) {
                            $query->where([['products.is_lp', false], ['products.is_periodic', false]]);
                        }
                        else {
                            $query->orWhere([['products.is_lp', false], ['products.is_periodic', false]]);
                        }
                    }
                    else if ($marketing_id == 2) {
                        if ($index == 0) {
                            $query->where([['products.is_lp', true], ['products.is_periodic', false]]);
                        }
                        else {
                            $query->orWhere([['products.is_lp', true], ['products.is_periodic', false]]);
                        }
                    }
                    else if ($marketing_id == 3) {
                        if ($index == 0) {
                            $query->where([['products.is_lp', false], ['products.is_periodic', true]]);
                        }
                        else {
                            $query->orWhere([['products.is_lp', false], ['products.is_periodic', true]]);
                        }
                    }
                    else if ($marketing_id == 4) {
                        if ($index == 0) {
                            $query->where([['products.is_lp', true], ['products.is_periodic', true]]);
                        }
                        else {
                            $query->orWhere([['products.is_lp', true], ['products.is_periodic', true]]);
                        }
                    }

                    $index = $index + 1;
                }
            });
        }
//数量
        if(isset($params["customer_product_volume_list"])){
            $volume = array_map('intval', $params['customer_product_volume_list']);
            $customers->where(function($query) use ($volume) {
                $query->whereNotNull('orders.confirmed_timestamp');
                $query->whereNull('orders.deleted_at');
                $query->whereIn('products.volume', $volume);
            });
        }
//商品名
        $product_name = $params['customer_product_name'];
        if (isset($product_name))
            $customers->where(function($query) use ($product_name) {
                $query->whereNotNull('orders.confirmed_timestamp');
                $query->whereNull('orders.deleted_at');
                $query->where('order_details.product_name', 'like', "%{$product_name}%");
            });
//商品コード
        $product_code = $params['customer_product_code'];
        if (isset($product_code))
            $customers->where(function($query) use ($product_code) {
                $query->whereNotNull('orders.confirmed_timestamp');
                $query->whereNull('orders.deleted_at');
                $query->where('order_details.product_code', $product_code);
            });

        if(isset($params["customer_buy_total_min"])
            || isset($params["customer_buy_total_max"])
            || isset($params["customer_buy_times_min"])
            || isset($params["customer_buy_times_max"])
            || isset($params["customer_buy_volume_min"])
            || isset($params["customer_buy_volume_max"])
            || isset($params["customer_last_buy_date_from"])
            || isset($params["customer_last_buy_date_to"])
            || isset($params["customer_withdrawal_date_from"])
            || isset($params["customer_withdrawal_date_to"])
        ){
            $customers->whereNotNull('orders.confirmed_timestamp');
            $customers->whereNull('orders.deleted_at');
        }
//購入金額
        $min = $params['customer_buy_total_min'];
        $max = $params['customer_buy_total_max'];
        if (isset($min) && isset($max))
            $customers->havingRaw("sum((order_details.price + order_details.tax) * order_details.quantity) between ? and ?", [$min, $max]);
        elseif (isset($min) && !isset($max))
            $customers->havingRaw('sum((order_details.price + order_details.tax) * order_details.quantity) >= ?', [$min]);
        elseif (!isset($min) && isset($max))
            $customers->havingRaw('sum((order_details.price + order_details.tax) * order_details.quantity) <= ?', [$max]);
//購入回数
        $min = $params['customer_buy_times_min'];
        $max = $params['customer_buy_times_max'];
        if (isset($min) && isset($max))
            $customers->havingRaw('count(order_details.id) between ? and ?', [$min, $max]);
        elseif (isset($min) && !isset($max))
            $customers->havingRaw('count(order_details.id) >= ?', [$min]);
        elseif (!isset($min) && isset($max))
            $customers->havingRaw('count(order_details.id) <= ?', [$max]);
//購入本数
        $min = $params['customer_buy_volume_min'];
        $max = $params['customer_buy_volume_max'];
        if (isset($min) && isset($max))
            $customers->havingRaw('sum(order_details.quantity * products.volume) between ? and ?', [$min, $max]);
        elseif (isset($min) && !isset($max))
            $customers->havingRaw('sum(order_details.quantity * products.volume) >= ?', [$min]);
        elseif (!isset($min) && isset($max))
            $customers->havingRaw('sum(order_details.quantity * products.volume) <= ?', [$max]);
//最終購入日
        $from = $params['customer_last_buy_date_from'];
        $to = $params['customer_last_buy_date_to'];
        if (isset($from) && isset($to))
            $customers->havingRaw('max(orders.created_at) between ? and ?', [$from." 00:00:00", $to." 23:59:59"]);
        elseif (isset($from) && !isset($to))
            $customers->havingRaw('max(orders.created_at) >= ?', [$from." 00:00:00"]);
        elseif (!isset($from) && isset($to))
            $customers->havingRaw('max(orders.created_at) <= ?', [$to." 23:59:59"]);
//離脱日
        $from = $params['customer_withdrawal_date_from'];
        $to = $params['customer_withdrawal_date_to'];
        if (isset($from) && isset($to))
            $customers->havingRaw("max(orders.created_at) + 180 * interval '1 day' between ? and ?", [$from." 00:00:00", $to." 23:59:59"]);
        elseif (isset($from) && !isset($to))
            $customers->havingRaw("max(orders.created_at) + 180 * interval '1 day' >= ?", [$from." 00:00:00"]);
        elseif (!isset($from) && isset($to))
            $customers->havingRaw("max(orders.created_at) + 180 * interval '1 day' <= ?", [$to." 23:59:59"]);
//使用/未使用
        if(isset($params["sample1"])){
            if($params["sample1"]==1 && isset($params['customer_item_lineup_sample1'])){
                $item_lineup_sample1 = array_map('intval', $params['customer_item_lineup_sample1']);
                $customers->where(function($query) use ($item_lineup_sample1) {
                    $query->whereNotNull('orders.confirmed_timestamp');
                    $query->whereNull('orders.deleted_at');
                    $query->whereIn('products.item_lineup_id', $item_lineup_sample1);
                });
            }elseif ($params["sample1"]==2 && isset($params['customer_item_lineup_sample1'])){
                $item_lineup_sample1 = array_map('intval', $params['customer_item_lineup_sample1']);
                $customers->where(function($query) use ($item_lineup_sample1) {
                    $query->whereNotNull('orders.confirmed_timestamp');
                    $query->whereNull('orders.deleted_at');
                    $query->whereNotIn('products.item_lineup_id', $item_lineup_sample1);
                });
            }
        }

        if(isset($params["customer_item_lineup_sampleA"]) || isset($params["customer_item_lineup_sampleB"]))
            $customers->leftJoin('periodic_orders', 'customers.id', 'periodic_orders.customer_id')
                ->leftJoin('periodic_order_details', 'periodic_orders.id', 'periodic_order_details.periodic_order_id')
                ->leftJoin('products', 'periodic_order_details.product_id', 'products.id');
//定期稼働条件（A）
        if(isset($params["sampleA"])){
            if($params["sampleA"]==1 && isset($params['customer_item_lineup_sampleA'])){
                $item_lineup_sampleA = array_map('intval', $params['customer_item_lineup_sampleA']);
                $customers->where(function($query) use ($item_lineup_sampleA) {
                    $query->whereNotNull('periodic_orders.confirmed_timestamp');
                    $query->whereNull('periodic_orders.deleted_at');
                    $query->whereIn('products.item_lineup_id', $item_lineup_sampleA);
                    $query->where('periodic_orders.stop_flag', false);
                });
            }elseif ($params["sampleA"]==2 && isset($params['customer_item_lineup_sampleA'])){
                $item_lineup_sampleA = array_map('intval', $params['customer_item_lineup_sampleA']);
                $customers->where(function($query) use ($item_lineup_sampleA) {
                    $query->whereNotNull('periodic_orders.confirmed_timestamp');
                    $query->whereNull('periodic_orders.deleted_at');
                    $query->whereIn('products.item_lineup_id', $item_lineup_sampleA);
                    $query->where('periodic_orders.stop_flag', true);
                });
            }elseif ($params["sampleA"]==3 && isset($params['customer_item_lineup_sampleA'])){
                $item_lineup_sampleA = array_map('intval', $params['customer_item_lineup_sampleA']);
                $customers->where(function($query) use ($item_lineup_sampleA) {
                    $query->whereNotNull('periodic_orders.confirmed_timestamp');
                    $query->whereNull('periodic_orders.deleted_at');
                    $query->whereNotIn('products.item_lineup_id', $item_lineup_sampleA);
                });
            }
        }
//定期稼働条件（B）
        if(isset($params["sampleB"])){
            if($params["sampleB"]==1 && isset($params['customer_item_lineup_sampleB'])){
                $item_lineup_sampleB = array_map('intval', $params['customer_item_lineup_sampleB']);
                $customers->where(function($query) use ($item_lineup_sampleB) {
                    $query->whereNotNull('periodic_orders.confirmed_timestamp');
                    $query->whereNull('periodic_orders.deleted_at');
                    $query->whereIn('products.item_lineup_id', $item_lineup_sampleB);
                    $query->where('periodic_orders.stop_flag', false);
                });
            }elseif ($params["sampleB"]==2 && isset($params['customer_item_lineup_sampleB'])){
                $item_lineup_sampleB = array_map('intval', $params['customer_item_lineup_sampleB']);
                $customers->where(function($query) use ($item_lineup_sampleB) {
                    $query->whereNotNull('periodic_orders.confirmed_timestamp');
                    $query->whereNull('periodic_orders.deleted_at');
                    $query->whereIn('products.item_lineup_id', $item_lineup_sampleB);
                    $query->where('periodic_orders.stop_flag', true);
                });
            }elseif ($params["sampleB"]==3 && isset($params['customer_item_lineup_sampleB'])){
                $item_lineup_sampleB = array_map('intval', $params['customer_item_lineup_sampleB']);
                $customers->where(function($query) use ($item_lineup_sampleB) {
                    $query->whereNotNull('periodic_orders.confirmed_timestamp');
                    $query->whereNull('periodic_orders.deleted_at');
                    $query->whereNotIn('products.item_lineup_id', $item_lineup_sampleB);
                });
            }
        }
        $customers->groupBy('customers.id');
        return $customers;
    }

    public function search($params)
    {
        $query = $this->searchQuery($params);
        $query->select(
            'customers.id',
            'customers.customer_status_id',
            'customers.prefecture_id',
            'customers.name01',
            'customers.name02',
            'customers.kana01',
            'customers.kana02',
            'customers.phone_number01',
            'customers.phone_number02',
            'customers.email',
            'customers.birthday',
            'customers.buy_times',
            'customers.buy_volume',
            'customers.advertising_media_code'
        );
        $number_per_page = isset($params['number_per_page']) ? $params['number_per_page'] : 100;
        $customers = $query->paginate($number_per_page, ['*'], 'page', isset($params['page']) ? $params['page'] : null);
        return $customers;
    }

    public function downloadCsv($params)
    {
        $customers = $this->searchQuery($params);
        $ids = $customers->select('customers.id')->pluck('id')->toArray();
        $query = Customer::whereIn('customers.id', $ids)
            ->join('prefectures','prefectures.id', 'customers.prefecture_id')
            ->leftJoin('pfm_statuses','pfm_statuses.id','customers.pfm_status_id')
            ->leftJoin('registration_routes','registration_routes.id','customers.registration_route_id');
        $searchResultExport = new SearchResultExport(1, $query);
        return $searchResultExport->download();
    }
}