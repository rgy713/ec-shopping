<?php

use Illuminate\Database\Seeder;
use App\Models\Masters\PurchaseRoute;
class InitPurchaseRoutesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $model = new PurchaseRoute();
        $model->id  ='1';
        $model->name='モバイル';
        $model->rank='0';
        $model->save();

        $model = new PurchaseRoute();
        $model->id  ='2';
        $model->name='スマートフォン';
        $model->rank='1';
        $model->save();

        $model = new PurchaseRoute();
        $model->id  ='10';
        $model->name='PC';
        $model->rank='2';
        $model->save();

        $model = new PurchaseRoute();
        $model->id  ='99';
        $model->name='管理画面';
        $model->rank='3';
        $model->save();

        $model = new PurchaseRoute();
        $model->id  ='11';
        $model->name='TEL';
        $model->rank='4';
        $model->save();

        $model = new PurchaseRoute();
        $model->id  ='12';
        $model->name='FAX';
        $model->rank='5';
        $model->save();

        $model = new PurchaseRoute();
        $model->id  ='13';
        $model->name='メール';
        $model->rank='6';
        $model->save();

        $model = new PurchaseRoute();
        $model->id  ='14';
        $model->name='ハガキ';
        $model->rank='7';
        $model->save();

        $model = new PurchaseRoute();
        $model->id  ='15';
        $model->name='テレマーケティング';
        $model->rank='8';
        $model->save();

        $model = new PurchaseRoute();
        $model->id  ='16';
        $model->name='卸';
        $model->rank='9';
        $model->save();
































    }
}
