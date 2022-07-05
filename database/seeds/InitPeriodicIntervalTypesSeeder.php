<?php

use Illuminate\Database\Seeder;
use App\Models\Masters\PeriodicIntervalType;

class InitPeriodicIntervalTypesSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $model = new PeriodicIntervalType();
        $model->id=1;
        $model->name="日数間隔";
        $model->rank=1;
        $model->class_name="";
        $model->save();

        $model = new PeriodicIntervalType();
        $model->id=2;
        $model->name="◯ヶ月毎、指定日";
        $model->rank=2;
        $model->class_name="";
        $model->save();


    }
}
