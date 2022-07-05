<?php

use Illuminate\Database\Seeder;
use App\Models\Masters\MediumType;

class InitMediumTypesSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $model = new MediumType();
        $model->id=1;
        $model->name="ラジオ";
        $model->rank=1;
        $model->save();

        $model = new MediumType();
        $model->id=2;
        $model->name="新聞";
        $model->rank=2;
        $model->save();

        $model = new MediumType();
        $model->id=3;
        $model->name="テレビ";
        $model->rank=3;
        $model->save();

        $model = new MediumType();
        $model->id=4;
        $model->name="アフィリエイト";
        $model->rank=3;
        $model->save();

    }
}
