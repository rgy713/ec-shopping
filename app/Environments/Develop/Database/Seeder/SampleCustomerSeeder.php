<?php

namespace App\Environments\Develop\Database\Seeder;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class SampleCustomerSeeder extends Seeder
{
    public function run()
    {
        $customer = new Customer();
        $customer->name01 = "本会員顧客1";
        $customer->name02 = "DUMMY1";
        $customer->kana01 = "コキャクイチ";
        $customer->kana02 = "ダミーイチ";
        $customer->email = "sample_customer01@balocco.info";
        $customer->zipcode = "1111111";
        $customer->prefecture_id = 13;
        $customer->address01 = "住所ダミー1";
        $customer->address02 = "住所ダミー2";
        $customer->phone_number01 = "0399999991";
        $customer->phone_number02 = "08099999991";
        $customer->customer_status_id = 2;
        $customer->confirmed_timestamp='2018-01-01 00:00:01';
        $customer->birthday="2010-05-05";

        //TODO:パスワード暗号化方法
        $customer->password = "123456";
        $customer->save();


        $customer = new Customer();
        $customer->name01 = "一般会員顧客2";
        $customer->name02 = "DUMMY2";
        $customer->kana01 = "コキャクニ";
        $customer->kana02 = "ダミーニ";
        $customer->email = "sample_customer02@balocco.info";
        $customer->zipcode = "2222222";
        $customer->prefecture_id = 18;
        $customer->address01 = "01-住所ダミー1";
        $customer->address02 = "01-住所ダミー2";
        $customer->phone_number01 = "0399999992";
        $customer->phone_number02 = "08099999992";
        $customer->customer_status_id = 3;
        $customer->confirmed_timestamp='2018-01-02 00:00:02';
        $customer->advertising_media_code='2004';//サンプル広告番号

        //TODO:パスワード暗号化方法
        $customer->password = "123456";
        $customer->save();

        //3.emailなしcustomer
        $customer = new Customer();
        $customer->name01 = "本会員emailなし顧客3";
        $customer->name02 = "DUMMY3";
        $customer->kana01 = "コキャクサン";
        $customer->kana02 = "ダミーサン";

        $customer->zipcode = "3333333";
        $customer->prefecture_id = 18;
        $customer->address01 = "03-住所ダミー1";
        $customer->address02 = "03-住所ダミー2";
        $customer->phone_number01 = "0399999993";
        $customer->phone_number02 = "08099999993";
        $customer->customer_status_id = 2;
        $customer->confirmed_timestamp='2018-01-03 00:00:03';

        //TODO:パスワード暗号化方法
        $customer->password = "123456";
        $customer->save();

        //4.emailなしcustomer
        $customer = new Customer();
        $customer->name01 = "一般会員emailがing";
        $customer->name02 = "DUMMY4";
        $customer->kana01 = "コキャクヨン";
        $customer->kana02 = "ダミーヨン";
        //
        $customer->email="ing@fleuri.cc";
        $customer->zipcode = "4444444";
        $customer->prefecture_id = 18;
        $customer->address01 = "04-住所ダミー1";
        $customer->address02 = "04-住所ダミー2";
        $customer->phone_number01 = "0399999994";
        $customer->phone_number02 = "08099999994";
        $customer->customer_status_id = 3;
        $customer->confirmed_timestamp='2018-01-04 00:00:04';

        //TODO:パスワード暗号化方法
        $customer->password = "123456";
        $customer->save();


        //5.1と複アカウント疑い（2項目）のパターン
        $customer = new Customer();
        $customer->name01 = "本会員顧客5";
        $customer->name02 = "DUMMY5";
        $customer->kana01 = "コキャクゴ";
        $customer->kana02 = "ダミーゴ";
        //
        $customer->email=null;
        $customer->zipcode = "1111111";
        $customer->prefecture_id = 13;
        $customer->address01 = "住所ダミー1";
        $customer->address02 = "住所ダミー2";
        $customer->phone_number01 = "0399999991";
        $customer->phone_number02 = "08099999991";
        $customer->customer_status_id = 3;
        $customer->confirmed_timestamp='2018-01-05 00:00:05';

        //TODO:パスワード暗号化方法
        $customer->password = "123456";
        $customer->save();


        //1と複アカウント疑い（4項目）
        $customer = new Customer();
        $customer->name01 = "本会員顧客1";
        $customer->name02 = "DUMMY1";
        $customer->kana01 = "コキャクロク";
        $customer->kana02 = "ダミーロク";
        $customer->email = "sample_customer06@balocco.info";
        $customer->zipcode = "1111111";
        $customer->prefecture_id = 13;
        $customer->address01 = "住所ダミー1";
        $customer->address02 = "住所ダミー2";
        $customer->phone_number01 = "0399999991";
        $customer->phone_number02 = "08099999991";
        $customer->customer_status_id = 2;
        $customer->confirmed_timestamp='2018-01-06 00:00:06';
        $customer->birthday="2010-05-05";

        //TODO:パスワード暗号化方法
        $customer->password = "123456";
        $customer->save();

        //1と複アカウント疑い（4項目、住所の半角、全角）
        $customer = new Customer();
        $customer->name01 = "本会員顧客1";
        $customer->name02 = "DUMMY1";
        $customer->kana01 = "コキャクナナ";
        $customer->kana02 = "ダミーナナ";
        $customer->email = "sample_customer07@balocco.info";
        $customer->zipcode = "1111111";
        $customer->prefecture_id = 13;
        $customer->address01 = "住所　ダ ミ  -１　";
        $customer->address02 = "住所ダミー2";
        $customer->phone_number01 = "0399999991";
        $customer->phone_number02 = "08099999991";
        $customer->customer_status_id = 2;
        $customer->confirmed_timestamp='2018-01-07 00:00:07';
        $customer->birthday="2010-05-05";

        //TODO:パスワード暗号化方法
        $customer->password = "123456";
        $customer->save();

    }

}