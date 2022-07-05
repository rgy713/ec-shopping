<?php

use Illuminate\Database\Seeder;
use App\Models\Delivery;

class InitDeliveriesSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $data=[
            ["2","2","配送無し(ダウンロード商品用)","なし","","","0","f"],
            ["3","3","ヤマト運輸(定期)","クロネコヤマト","","http://toi.kuronekoyamato.co.jp/cgi-bin/tneko","2","f"],
            ["1","1","ヤマト運輸","クロネコヤマト","","http://toi.kuronekoyamato.co.jp/cgi-bin/tneko","3","f"],
            ["4","4","ヤマト運輸(LP)","クロネコヤマト","","http://toi.kuronekoyamato.co.jp/cgi-bin/tneko","4","f"],
            ["5","5","ヤマト運輸(サンプル)","クロネコヤマト","","http://toi.kuronekoyamato.co.jp/cgi-bin/tneko","5","t"],
            ["8","3","日本郵政(定期)","ゆうパック","","https://trackings.post.japanpost.jp/services/srv/search/","6","f"],
            ["9","1","日本郵政","ゆうパック","","https://trackings.post.japanpost.jp/services/srv/search/","7","f"],
            ["7","4","日本郵政(LP)","ゆうパック","","https://trackings.post.japanpost.jp/services/srv/search/","8","f"],
            ["6","5","日本郵政(ゆうメール)","日本郵政","美容液10日間実感セット用","https://trackings.post.japanpost.jp/services/srv/search/","9","f"],
            ["11","3","佐川急便（定期）","佐川急便","","http://k2k.sagawa-exp.co.jp/p/sagawa/web/okurijoinput.jsp","10","t"],
            ["10","1","佐川急便（通常）","佐川急便","","http://k2k.sagawa-exp.co.jp/p/sagawa/web/okurijoinput.jsp","11","t"],
            ["12","4","佐川急便（LP）","佐川急便","","http://k2k.sagawa-exp.co.jp/p/sagawa/web/okurijoinput.jsp","12","t"],
            ["13","5","佐川急便（サンプル）","佐川急便","","http://k2k.sagawa-exp.co.jp/p/sagawa/web/okurijoinput.jsp","13","t"],
        ];

        foreach ($data as $item){
            $model = new Delivery();
            $model->id=$item[0];
            $model->product_delivery_type_id=$item[1];
            $model->name=$item[2];
            $model->service_name=$item[3];
            $model->remark=$item[4];
            $model->confirm_url=$item[5];
            $model->rank=$item[6];
            $model->user_visible=$item[7];
            $model->save();
        }
    }
}
