<?php

namespace App\Environments\Develop\Database\Seeder;

use App\Models\Delivery;
use App\Models\Order;
use App\Models\PeriodicOrder;
use App\Models\PeriodicShipping;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SamplePeriodicOrderSeeder extends Seeder
{
    public function run()
    {
        $model = new PeriodicOrder();
        $model->customer_id = 1;

        $model->name01 = "定期1苗字";
        $model->name02 = "定期1名前";
        $model->kana01 = "テイキイチミョウジ";
        $model->kana02 = "テイキイチナマエ";
        $model->email = "sample_periodic@balocco.info";
        $model->zipcode = "1111111";
        $model->prefecture_id = 13;
        $model->address01 = "住所定期1ダミー01";
        $model->address02 = "住所定期1ダミー02";
        $model->phone_number01 = "0399999991";
        $model->phone_number02 = "08099999991";

        $model->discount=0;//値引きなし
        $model->payment_method_id=4;//代引き
        $model->periodic_count=0;//定期回0
        $model->stop_flag=false;//稼働中
        $model->failed_flag=false;

        $model->periodic_interval_type_id=1;//日数間隔
        $model->interval_days=60;//60日間隔
        $model->next_delivery_date=Carbon::now();
        $model->last_delivery_date=null;//定期回0（まだ届けていない）想定

        //前回の配送情報（定期回0のため、管理画面で入力した配送方法に紐づく値を保持している想定）
        $model->last_delivery_id=11;//佐川（定期）
        $model->last_delivery_fee=0;//配送料：0円
        $model->last_delivery_fee_tax=0;//配送料税額：0円

        //前回の支払情報（定期回0のため、管理画面で入力した支払い方法に紐づく値を保持している想定）
        $model->last_payment_method_fee=0;//支払手数料：0円
        $model->last_payment_method_fee_tax=0;//支払手数料消費税額：0円

        //前回の金額情報（定期回0のため、管理画面登録時の商品構成（ID、数量）により計算された金額を保持している想定）

        //金額
        $model->last_subtotal=5696;
        $model->last_subtotal_tax=456;
        $model->last_total=5696;
        $model->last_total_tax=456;
        $model->last_payment_total=5696;
        $model->last_payment_total_tax=456;

        //その他情報
        $model->purchase_route_id=11;//TEL(管理画面での購入)
        $model->confirmed_timestamp=Carbon::yesterday();
        $model->save();

        $model->details()->create([
            "product_id"=>19,
            "product_name"=>'クリアゲルクレンズ(定期宅配便)',
            "product_code"=>'TEL-0001',
            "quantity"=>2,
            "volume"=>1,
        ]);

        //配送情報を作成
        $shipping=new PeriodicShipping();
        $shipping->periodic_order_id=$model->id;
        $shipping->name01='定期配送先苗字';
        $shipping->name02='定期配送先名前';
        $shipping->kana01='テイキハイソウサキミョウジ';
        $shipping->kana02='テイキハイソウサキナマエ';
        $shipping->zipcode='1111111';
        $shipping->prefecture_id=1;
        $shipping->address01='配送先住所市区町村';
        $shipping->address02='配送先住所番地以降';
        $shipping->requests_for_delivery='';
        $shipping->phone_number01='0123456789';
        $delivery=Delivery::find(1);
        $shipping->last_delivery_id=$delivery->id;
        $shipping->save();




    }

}