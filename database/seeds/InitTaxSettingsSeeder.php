<?php

use Illuminate\Database\Seeder;
use App\Models\TaxSettings;

class InitTaxSettingsSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $model = new TaxSettings();
        $model->name='税率5%設定';
        $model->rate=0.05;
        $model->activated_from='1997-04-01 00:00:00';
        $model->save();

        $model = new TaxSettings();
        $model->name='税率8%設定';
        $model->rate=0.08;
        $model->activated_from='2014-04-01 00:00:00';
        $model->save();


        $model = new TaxSettings();
        $model->name='税率10%設定';
        $model->rate=0.1;
        $model->activated_from='2019-10-01 00:00:00';
        $model->save();

    }
}
