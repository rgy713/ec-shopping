<?php

use Illuminate\Database\Seeder;
use App\Models\Masters\UndeliveredSummaryClassification;

class InitUndeliveredSummaryClassificationsSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $data=[
            [1,"通常販売",1],
            [2,"サンプル販売",2],
            [3,"無料プレゼント",3],

        ];
        foreach ($data as $item){
            $model=new UndeliveredSummaryClassification();
            $model->id=$item[0];
            $model->name=$item[1];
            $model->rank=$item[2];
            $model->save();

        }



    }
}
