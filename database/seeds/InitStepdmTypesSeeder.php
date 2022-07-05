<?php

use Illuminate\Database\Seeder;
use App\Models\Masters\StepdmType;

class InitStepdmTypesSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $model=new StepdmType();
        $model->id=1;
        $model->name="停止なし";
        $model->rank=1;
        $model->class_name="StepDmStrategyType1";
        $model->save();

        $model=new StepdmType();
        $model->id=2;
        $model->name="CGC-LP1,CGC-SPL1/HD1001～HD1030 CGC-TTT定期稼動ユーザー除外";
        $model->rank=2;
        $model->class_name="StepDmStrategyType2";
        $model->save();

        $model=new StepdmType();
        $model->id=3;
        $model->name="TPR-SPL1/HD4001～HD4030 TPR-TTT定期稼動ユーザー除外";
        $model->rank=3;
        $model->class_name="StepDmStrategyType3";
        $model->save();
    }
}
