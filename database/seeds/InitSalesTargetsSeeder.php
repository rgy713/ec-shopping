<?php

use Illuminate\Database\Seeder;
use App\Models\Masters\SalesTarget;

class InitSalesTargetsSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $model = new SalesTarget();
        $model->id = 1;
        $model->name = '通販';
        $model->rank = 1;
        $model->save();

        $model = new SalesTarget();
        $model->id = 2;
        $model->name = '卸';
        $model->rank = 2;
        $model->save();
    }
}
