<?php


namespace App\Services;

use App\Events\CustomerRegistered;
use App\Models\Customer;
use App\Models\Exports\SearchResultExport;
use App\Models\Order;
use App\Models\PeriodicOrder;
use App\Models\ShopMemo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomerService
{
    /**
     * @param $params
     * @return Customer
     */
    public function createSave($params)
    {
        $customer = new Customer();
        $customer->customer_status_id = $params["customer_status"];
        $customer->wholesale_flag = $params["customer_wholesale_flag"] == 0 ? TRUE : FALSE;
        $customer->email = isset($params["customer_email"]) ? $params["customer_email"] : null;
        $customer->password = isset($params["customer_password"]) ? $params["customer_password"] : null;
        $customer->name01 = $params["customer_name01"];
        $customer->name02 = $params["customer_name02"];
        $customer->kana01 = $params["customer_kana01"];
        $customer->kana02 = $params["customer_kana02"];
        $customer->phone_number01 = $params["customer_phone_number01"];
        $customer->phone_number02 = isset($params["customer_phone_number02"]) ? $params["customer_phone_number02"] : null;
        $customer->zipcode = $params["customer_zipcode"];
        $customer->prefecture_id = $params["customer_prefecture"];
        $customer->address01 = $params["customer_address1"];
        $customer->address02 = $params["customer_address2"];
        $customer->requests_for_delivery = isset($params["customer_address3"]) ? $params["customer_address3"] : null;
        $customer->advertising_media_code = isset($params["customer_advertising_media_code"]) ? $params["customer_advertising_media_code"] : null;
        $customer->birthday = $params["customer_birthday"];
        $customer->no_phone_call_flag = (isset($params["customer_no_phone_call"]) && $params["customer_no_phone_call"] == 1) ? TRUE : FAlSE;
        $customer->mail_magazine_flag = $params["customer_mail_flag"] == 2 ? TRUE : FAlSE;
        $customer->dm_flag = $params["customer_direct_mail_flag"] == 1 ? TRUE : FAlSE;
        $customer->save();

        if (isset($params["shop_memo_note"])) {
            $shop_memo = new ShopMemo();
            $shop_memo->note = $params["shop_memo_note"];
            $shop_memo->important = isset($params["shop_memo_important"]) ? TRUE : FALSE;
            $shop_memo->claim_flag = isset($params["shop_memo_claim_flag"]) ? TRUE : FALSE;
            $shop_memo->customer_id = $customer->id;
            $shop_memo->created_by = Auth::user()->id;
            $shop_memo->save();
        }

        //保存完了時、CustomerRegisteredイベントを発火する。
        event(new CustomerRegistered($customer));

        return $customer;
    }

    public function update($params)
    {
        $customer = app(Customer::class)->find($params["id"]);
        $customer->customer_status_id = $params["customer_status"];
        $customer->wholesale_flag = $params["customer_wholesale_flag"] == 0 ? TRUE : FALSE;
        $customer->email = isset($params["customer_email"]) ? $params["customer_email"] : null;
        $customer->password = isset($params["customer_password"]) ? $params["customer_password"] : null;
        $customer->name01 = $params["customer_name01"];
        $customer->name02 = $params["customer_name02"];
        $customer->kana01 = $params["customer_kana01"];
        $customer->kana02 = $params["customer_kana02"];
        $customer->phone_number01 = $params["customer_phone_number01"];
        $customer->phone_number02 = isset($params["customer_phone_number02"]) ? $params["customer_phone_number02"] : null;
        $customer->zipcode = $params["customer_zipcode"];
        $customer->prefecture_id = $params["customer_prefecture"];
        $customer->address01 = $params["customer_address1"];
        $customer->address02 = $params["customer_address2"];
        $customer->requests_for_delivery = isset($params["customer_address3"]) ? $params["customer_address3"] : null;
        $customer->birthday = $params["customer_birthday"];
        $customer->no_phone_call_flag = (isset($params["customer_no_phone_call"]) && $params["customer_no_phone_call"] == 1) ? TRUE : FAlSE;
        $customer->mail_magazine_flag = $params["customer_mail_flag"] == 2 ? TRUE : FAlSE;
        $customer->dm_flag = $params["customer_direct_mail_flag"] == 1 ? TRUE : FAlSE;

        $admin = Auth::user();

        $media_readonly = false;
        if ($admin->admin_role_id == 4 || $admin->admin_role_id == 6) {
            $media_readonly = true;
        } elseif ($admin->admin_role_id == 3 || $admin->admin_role_id == 5 || $admin->admin_role_id == 7) {
            if (isset($customer) && ($customer->advertising_media_code < 9000 || $customer->advertising_media_code > 9999))
                $media_readonly = true;
        }
        if (!$media_readonly)
            $customer->advertising_media_code = isset($params["customer_advertising_media_code"]) ? $params["customer_advertising_media_code"] : null;

        $customer->save();

        if (isset($params["shop_memo_note"])) {
            $shop_memo = new ShopMemo();
            $shop_memo->note = $params["shop_memo_note"];
            $shop_memo->important = isset($params["shop_memo_important"]) ? TRUE : FALSE;
            $shop_memo->claim_flag = isset($params["shop_memo_claim_flag"]) ? TRUE : FALSE;
            $shop_memo->customer_id = $customer->id;
            $shop_memo->created_by = $admin->id;
            $shop_memo->save();
        }
    }

    public function search($params)
    {
        $customers = app(Customer::class)->select();
//名前
        if (isset($params['customer_name']))
            $customers->where(DB::Raw("concat(name01, '', name02)"), 'like', "%{$params['customer_name']}%");
//フリガナ
        if (isset($params['customer_kana']))
            $customers->where(DB::Raw("concat(kana01, '', kana02)"), 'like', "%{$params['customer_kana']}%");
//Email
        if (isset($params['customer_email']))
            $customers->where('email', $params['customer_email']);
//電話番号
        if (isset($params['customer_phone'])) {
            $columns = ['phone_number01', 'phone_number02'];
            $value = $params['customer_phone'];
            $customers->where(function ($q) use ($columns, $value) {
                foreach ($columns as $column) {
                    $q->orWhere($column, $value);
                }
            });
        }
        $number_per_page = isset($params['number_per_page']) ? $params['number_per_page'] : 100;
        $customers = $customers->paginate($number_per_page, ['*'], 'page', isset($params['page']) ? $params['page'] : null);
        return $customers;
    }

    public function delete($id)
    {
        app(Customer::class)->find($id)->delete();
    }

    protected function detailSearchQuery($params)
    {
        $customers = app(Customer::class)->select();
        //顧客ID
        if (isset($params['customer_id']))
            $customers->where('id', $params['customer_id']);
        //都道府県
        if (isset($params['customer_prefecture_id']))
            $customers->where('prefecture_id', $params['customer_prefecture_id']);
        //名前
        if (isset($params['customer_name']))
            $customers->where(DB::Raw("concat(name01, '', name02)"), 'like', "%{$params['customer_name']}%");
        //フリガナ
        if (isset($params['customer_kana']))
            $customers->where(DB::Raw("concat(kana01, '', kana02)"), 'like', "%{$params['customer_kana']}%");
        //Email
        if (isset($params['customer_email']))
            $customers->where('email', $params['customer_email']);
        //電話番号
        if (isset($params['customer_phone'])) {
            $columns = ['phone_number01', 'phone_number02'];
            $value = $params['customer_phone'];
            $customers->where(function ($q) use ($columns, $value) {
                foreach ($columns as $column) {
                    $q->orWhere($column, $value);
                }
            });
        }
        //広告番号
        if (isset($params['customer_media_code']))
            $customers->where('advertising_media_code', $params['customer_media_code']);
        //誕生日
        $from = $params['customer_birthday_from'];
        $to = $params['customer_birthday_to'];
        if (isset($from) && isset($to))
            $customers->whereBetween('birthday', [date($from), date($to)]);
        elseif (isset($from) && !isset($to))
            $customers->whereDate('birthday', '>=', date($from));
        elseif (!isset($from) && isset($to))
            $customers->whereDate('birthday', '<=', date($to));
        //購入金額
        $min = $params['customer_buy_total_min'];
        $max = $params['customer_buy_total_max'];
        if (isset($min) && isset($max))
            $customers->whereBetween('buy_total', [$min, $max]);
        elseif (isset($min) && !isset($max))
            $customers->where('buy_total', '>=', $min);
        elseif (!isset($min) && isset($max))
            $customers->where('buy_total', '<=', $max);
        //最終購入日
        $from = $params['customer_last_buy_date_from'];
        $to = $params['customer_last_buy_date_to'];
        if (isset($from) && isset($to))
            $customers->whereBetween('last_buy_date', [$from." 00:00:00", $to." 23:59:59"]);
        elseif (isset($from) && !isset($to))
            $customers->where('last_buy_date', '>=', $from." 00:00:00");
        elseif (!isset($from) && isset($to))
            $customers->where('last_buy_date', '<=', $to." 23:59:59");
        //商品名
        $product_name = $params['customer_product_name'];
        if (isset($product_name)) {
            $customers->whereHas('orders', function ($q) use ($product_name) {
                $q->whereNull('canceled_timestamp');
                $q->whereHas('details', function ($q) use ($product_name) {
                    $q->where('product_name', 'like', "%{$product_name}%");
                });
            });
        }
        //商品コード
        $product_code = $params['customer_product_code'];
        if (isset($product_code)) {
            $customers->whereHas('orders', function ($q) use ($product_code) {
                $q->whereHas('details', function ($q) use ($product_code) {
                    $q->where('product_code', $product_code);
                });
            });
        }
        //会員状態
        if (isset($params['customer_status']))
            $customers->whereIn('customer_status_id', array_map('intval', $params['customer_status']));
        //定期ID
        $min = $params['customer_periodic_id_min'];
        $max = $params['customer_periodic_id_max'];
        if (isset($min) && isset($max))
            $customers->whereHas('periodicOrders', function ($q) use ($min, $max) {
                $q->whereBetween('id', [$min, $max]);
            });
        elseif (isset($min) && !isset($max))
            $customers->whereHas('periodicOrders', function ($q) use ($min) {
                $q->where('id', '>=', $min);
            });
        elseif (!isset($min) && isset($max))
            $customers->whereHas('periodicOrders', function ($q) use ($max) {
                $q->where('id', '<=', $max);
            });
        //定期回数
        $min = $params['customer_periodic_count_min'];
        $max = $params['customer_periodic_count_max'];
        if (isset($min) && isset($max))
            $customers->whereHas('periodicOrders', function ($q) use ($min, $max) {
                $q->whereBetween('periodic_count', [$min, $max]);
            });
        elseif (isset($min) && !isset($max))
            $customers->whereHas('periodicOrders', function ($q) use ($min) {
                $q->where('periodic_count', '>=', $min);
            });
        elseif (!isset($min) && isset($max))
            $customers->whereHas('periodicOrders', function ($q) use ($max) {
                $q->where('periodic_count', '<=', $max);
            });
        //対応状況
        $periodic_status = $params['customer_periodic_status_flag'];
        if(isset($periodic_status))
            $customers->whereHas('periodicOrders', function($q) use ($periodic_status){
                $q->where('failed_flag', $periodic_status ? true: false);
            });
        //稼働状況
        $periodic_stop = $params['customer_periodic_stop_flag'];
        if(isset($periodic_stop))
            $customers->whereHas('periodicOrders', function($q) use ($periodic_stop){
                $q->where('stop_flag', $periodic_stop ? true: false);
            });
        //ラインナップ
        if(isset($params['customer_product_lineup'])){
            $product_lineup = array_map('intval', $params['customer_product_lineup']);
            $customers->whereHas('periodicOrders', function($q) use ($product_lineup){
                $q->whereHas('details', function($q) use ($product_lineup){
                    $q->whereHas('product', function($q) use ($product_lineup){
                        $q->whereIn('item_lineup_id', $product_lineup);
                    });
                });
            });
        }
        //支払い方法
        if(isset($params['customer_payment_method'])){
            $method_id = array_map('intval', $params['customer_payment_method']);
            $customers->whereHas('periodicOrders', function($q) use ($method_id){
                $q->whereIn('payment_method_id', $method_id);
            });
        }
        //前回到着
        $from = $params['customer_periodic_prev_create_from'];
        $to = $params['customer_periodic_prev_create_to'];
        if (isset($from) && isset($to))
            $customers->whereHas('periodicOrders', function($q) use ($from, $to){
                $q->whereBetween('last_delivery_date', [date($from), date($to)]);
            });
        elseif (isset($from) && !isset($to))
            $customers->whereHas('periodicOrders', function($q) use ($from, $to){
                $q->whereDate('last_delivery_date', '>=', date($from));
            });
        elseif (!isset($from) && isset($to))
            $customers->whereHas('periodicOrders', function($q) use ($from, $to){
                $q->whereDate('last_delivery_date','<=', date($to));
            });
        //次回到着
        $from = $params['customer_periodic_next_create_from'];
        $to = $params['customer_periodic_next_create_to'];
        if (isset($from) && isset($to))
            $customers->whereHas('periodicOrders', function($q) use ($from, $to){
                $q->whereBetween('next_delivery_date', [date($from), date($to)]);
            });
        elseif (isset($from) && !isset($to))
            $customers->whereHas('periodicOrders', function($q) use ($from, $to){
                $q->whereDate('next_delivery_date', '>=', date($from));
            });
        elseif (!isset($from) && isset($to))
            $customers->whereHas('periodicOrders', function($q) use ($from, $to){
                $q->whereDate('next_delivery_date','<=', date($to));
            });
        return $customers;
    }

    public function detailSearch($params)
    {
        $customers = $this->detailSearchQuery($params);
        $number_per_page = isset($params['number_per_page']) ? $params['number_per_page'] : 100;
        $customers = $customers->paginate($number_per_page, ['*'], 'page', isset($params['page']) ? $params['page'] : null);
        return $customers;
    }

    public function downloadCsv($params)
    {
        $customers = $this->detailSearchQuery($params);
        $ids = $customers->pluck('id')->toArray();
        $query = Customer::whereIn('customers.id', $ids)
            ->join('prefectures','prefectures.id', 'customers.prefecture_id')
            ->leftJoin('pfm_statuses','pfm_statuses.id','customers.pfm_status_id')
            ->leftJoin('registration_routes','registration_routes.id','customers.registration_route_id');
        $searchResultExport = new SearchResultExport(1, $query);
        return $searchResultExport->download();
    }
}