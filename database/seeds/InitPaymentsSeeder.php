<?php

use Illuminate\Database\Seeder;
use App\Models\Masters\PaymentMethod;

class InitPaymentsSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $model=new PaymentMethod();
        $model->id=4;
        $model->name='代金引換';
        $model->lower_limit=0;
        $model->fee=0;
        $model->rank=3;
        $model->remark='ID：4、代引き。旧システムから継続利用。';
        $model->user_visible=true;
        $model->admin_visible=true;
        $model->initial_order_status_id=1;//新規受付
        $model->initial_periodic_batch_order_status_id=9;//定期受付
        $model->save();

        $model=new PaymentMethod();
        $model->id=8;
        $model->name='郵便振替';
        $model->lower_limit=0;
        $model->fee=0;
        $model->rank=1;
        $model->remark='ID：8、郵便振替。旧システムから継続利用。ユーザー画面では利用不可。';
        $model->user_visible=true;
        $model->admin_visible=true;
        $model->initial_order_status_id=1;//新規受付
        $model->initial_periodic_batch_order_status_id=9;//定期受付
        $model->save();

        $model=new PaymentMethod();
        $model->id=6;
        $model->name='銀行振込';
        $model->lower_limit=0;
        $model->fee=0;
        $model->rank=2;
        $model->remark='ID：6、銀行振込。旧システムから継続利用。ユーザー画面では利用不可。';
        $model->user_visible=true;
        $model->admin_visible=true;
        $model->initial_order_status_id=1;//新規受付
        $model->initial_periodic_batch_order_status_id=9;//定期受付
        $model->save();

        $model=new PaymentMethod();
        $model->id=5;
        $model->name='クレジット';
        $model->lower_limit=0;
        $model->fee=0;
        $model->rank=4;
        $model->remark='ID：5、クレジット（ペイジェント）。旧システムから継続利用。';
        $model->user_visible=true;
        $model->admin_visible=true;
        $model->initial_order_status_id=22;//決済処理前
        $model->initial_periodic_batch_order_status_id=22;//決済処理前

        $model->settlement_vendor='paygent';
        $model->settlement_vendor_sub_code='credit';
        $model->save();
    }

}
