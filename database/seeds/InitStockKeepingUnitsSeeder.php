<?php

use Illuminate\Database\Seeder;
use App\Models\StockKeepingUnit;

class InitStockKeepingUnitsSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $model=new StockKeepingUnit();
        $model->name="クリアゲルクレンズ 本品";
        $model->unit_name="本";
        $model->save();

        $model=new StockKeepingUnit();
        $model->name="クリアゲルクレンズ 試用パウチ6包";
        $model->unit_name="個";
        $model->save();

        $model=new StockKeepingUnit();
        $model->name="ミネラルモイストソープ 本品";
        $model->unit_name="個";
        $model->save();

        $model=new StockKeepingUnit();
        $model->name="リファイニングミスト 本品";
        $model->unit_name="本";
        $model->save();

        $model=new StockKeepingUnit();
        $model->name="トリプルリペア 本品";
        $model->unit_name="本";
        $model->save();

        $model=new StockKeepingUnit();
        $model->name="トリプルリペア 試用ボトル10ml";
        $model->unit_name="本";
        $model->save();

    }
}
