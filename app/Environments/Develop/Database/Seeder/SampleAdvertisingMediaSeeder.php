<?php

namespace App\Environments\Develop\Database\Seeder;

use App\Models\AdvertisingMedia;
use Illuminate\Database\Seeder;

class SampleAdvertisingMediaSeeder extends Seeder
{
    public function run()
    {
        $model = new AdvertisingMedia();
        $model->code=2004;
        $model->name='ラジオ大阪「ほんまもん!原田年晴です」';
        $model->media_type_id=1;//ラジオ
        $model->date='2014-05-02';
        $model->start_time='14:42:00';
        $model->cost=97200;
        $model->item_lineup_id=1;//クリアゲルクレンズ
        $model->creator_admin_id=1;//
        $model->save();


        $model = new AdvertisingMedia();
        $model->code=70496;
        $model->name='2019.02 アフィリエイト:Instagram（frontdeal）(ヴァンクリーフ)';
        $model->media_type_id=4;//ラジオ
        $model->date='2019-02-01';
        $model->start_time='00:00:00';
        $model->cost=0;
        $model->item_lineup_id=1;//クリアゲルクレンズ
        $model->asp_flag=true;
        $model->asp_from='2019-02-01 00:00:00';
        $model->asp_to='2019-03-01 00:00:00';
        $model->asp_name='Instagram（frontdeal）';
        $model->creator_admin_id=1;//
        $model->save();

    }

}