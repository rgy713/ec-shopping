<?php

namespace App\Environments\Develop\Database\Seeder;

use App\Models\Delivery;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Shipping;
use Illuminate\Database\Seeder;

class SampleOrderSeeder extends Seeder
{
    public function run()
    {
        $model = new Order();
        $model->customer_id = 1;

        $model->name01 = "本会員顧客1";
        $model->name02 = "DUMMY1";
        $model->kana01 = "コキャクイチ";
        $model->kana02 = "ダミーイチ";
        $model->email = "sample_customer01@balocco.info";
        $model->zipcode = "1111111";
        $model->prefecture_id = 13;
        $model->address01 = "住所ダミー1";
        $model->address02 = "住所ダミー2";
        $model->phone_number01 = "0399999991";
        $model->phone_number02 = "08099999991";
        $model->confirmed_timestamp='2019-03-17 12:00:00';

        //受注ステータス
        $model->order_status_id=1;

        //金額系
        $model->tax_rate = 0.08;
        $model->subtotal = 11900;
        $model->subtotal_tax = 952;
        $model->total = 12500;
        $model->total_tax = 1000;
        $model->discount = 200;
        $model->payment_total = 12300;
        $model->payment_total_tax = 984;
        $model->catalog_price_subtotal = 12500;
        $model->catalog_price_subtotal_tax = 1000;
        $model->discount_from_catalog_price = 600;
        $model->discount_from_catalog_price_tax = 48;


        //配送方法：佐川急便（通常）
        $model->delivery_id = 10;
        $model->delivery_name = '佐川急便（通常）';
        $model->delivery_fee = 600;
        $model->delivery_fee_tax = 48;

        //支払い方法：代引き
        $model->payment_method_id = 5;
        $model->payment_method_name = "代金引換";
        $model->payment_method_fee = 0;
        $model->payment_method_fee_tax = 0;

        $model->save();

        $detail = new OrderDetail();
        $detail->order_id=$model->id;
        $detail->product_id=12;
        $detail->price=2900;
        $detail->tax=232;
        $detail->quantity=1;
        $detail->product_name='クリアゲルクレンズ(初回限定)';
        $detail->product_code='FRS-0001';
        $detail->catalog_price=3500;
        $detail->catalog_price_tax=280;
        $detail->volume=1;
        $detail->save();

        $detail = new OrderDetail();
        $detail->order_id=$model->id;
        $detail->product_id=114;
        $detail->price=4500;
        $detail->tax=360;
        $detail->quantity=2;
        $detail->product_name='リファイニングミスト';
        $detail->product_code='RFM-0001';
        $detail->catalog_price=4500;
        $detail->catalog_price_tax=360;
        $detail->volume=1;
        $detail->save();

        //配送情報を作成
        $shipping=new Shipping();
        $shipping->order_id=$model->id;
        $shipping->name01='配送先苗字';
        $shipping->name02='配送先名前';
        $shipping->kana01='ハイソウサキミョウジ';
        $shipping->kana02='ハイソウサキナマエ';
        $shipping->zipcode='1111111';
        $shipping->prefecture_id=1;
        $shipping->address01='配送先住所市区町村';
        $shipping->address02='配送先住所番地以降';
        $shipping->requests_for_delivery='';
        $shipping->phone_number01='0123456789';
        $delivery=Delivery::find(1);
        $shipping->delivery_id=$delivery->id;
        $shipping->delivery_name=$delivery->name;
        $shipping->delivery_time_id=null;
        $shipping->save();


        /**
         *
         */
        $model = new Order();
        $model->customer_id = 1;

        $model->name01 = "本会員顧客1";
        $model->name02 = "DUMMY1";
        $model->kana01 = "コキャクイチ";
        $model->kana02 = "ダミーイチ";
        $model->email = "sample_customer01@balocco.info";
        $model->zipcode = "1111111";
        $model->prefecture_id = 13;
        $model->address01 = "住所ダミー1";
        $model->address02 = "住所ダミー2";
        $model->phone_number01 = "0399999991";
        $model->phone_number02 = "08099999991";
        $model->confirmed_timestamp='2019-03-17 12:00:00';

        //受注ステータス
        $model->order_status_id=1;

        //金額系
        $model->tax_rate = 0.08;
        $model->subtotal = 1000;
        $model->subtotal_tax = 80;
        $model->total = 1600;
        $model->total_tax = 128;
        $model->discount = 200;
        $model->payment_total = 1400;
        $model->payment_total_tax = 112;
        $model->catalog_price_subtotal = 1900;
        $model->catalog_price_subtotal_tax = 152;
        $model->discount_from_catalog_price = 900;
        $model->discount_from_catalog_price_tax = 72;


        //配送方法：佐川急便（通常）
        $model->delivery_id = 10;
        $model->delivery_name = '佐川急便（通常）';
        $model->delivery_fee = 600;
        $model->delivery_fee_tax = 48;

        //支払い方法：代引き
        $model->payment_method_id = 5;
        $model->payment_method_name = "代金引換";
        $model->payment_method_fee = 0;
        $model->payment_method_fee_tax = 0;

        $model->save();

        //配送情報を作成
        $shipping=new Shipping();
        $shipping->order_id=$model->id;
        $shipping->name01='配送先苗字';
        $shipping->name02='配送先名前';
        $shipping->kana01='ハイソウサキミョウジ';
        $shipping->kana02='ハイソウサキナマエ';
        $shipping->zipcode='1111111';
        $shipping->prefecture_id=1;
        $shipping->address01='配送先住所市区町村';
        $shipping->address02='配送先住所番地以降';
        $shipping->requests_for_delivery='';
        $shipping->phone_number01='0123456789';
        $delivery=Delivery::find(1);
        $shipping->delivery_id=$delivery->id;
        $shipping->delivery_name=$delivery->name;
        $shipping->delivery_time_id=null;
        $shipping->save();

    }

}