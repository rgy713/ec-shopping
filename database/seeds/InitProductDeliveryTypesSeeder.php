<?php

use Illuminate\Database\Seeder;
use App\Models\Masters\ProductDeliveryType;

class InitProductDeliveryTypesSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $model = new ProductDeliveryType();
        $model->id = 1;
        $model->name = '通常';
        $model->rank = 1;
        $model->save();

        $model = new ProductDeliveryType();
        $model->id = 2;
        $model->name = 'ダウンロード';
        $model->rank =99;
        $model->save();

        $model = new ProductDeliveryType();
        $model->id = 3;
        $model->name = '定期';
        $model->rank = 2;
        $model->save();

        $model = new ProductDeliveryType();
        $model->id = 4;
        $model->name = 'LP';
        $model->rank = 3;
        $model->save();

        $model = new ProductDeliveryType();
        $model->id = 5;
        $model->name = 'サンプル';
        $model->rank = 4;
        $model->save();

        $model = new ProductDeliveryType();
        $model->id = 6;
        $model->name = '直定期';
        $model->rank = 5;
        $model->save();

    }
}
