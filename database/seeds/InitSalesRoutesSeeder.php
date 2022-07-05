<?php

use Illuminate\Database\Seeder;
use App\Models\Masters\SalesRoute;

class InitSalesRoutesSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $model = new SalesRoute();
        $model->id = 1;
        $model->name = 'EC';
        $model->rank = 1;
        $model->save();

        $model = new SalesRoute();
        $model->id = 2;
        $model->name = 'LP';
        $model->rank = 2;
        $model->save();

        $model = new SalesRoute();
        $model->id = 3;
        $model->name = 'TEL';
        $model->rank = 3;
        $model->save();
    }
}
