<?php

use Illuminate\Database\Seeder;
use App\Models\Masters\PurchaseWarningType;

class InitPurchaseWarningTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $model = new PurchaseWarningType();
        $model->id=1;
        $model->name="購入不可";
        $model->save();

        $model = new PurchaseWarningType();
        $model->id=2;
        $model->name="購入時警告（赤エラー）";
        $model->save();
    }
}
