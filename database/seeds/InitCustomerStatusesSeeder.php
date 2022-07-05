<?php

use Illuminate\Database\Seeder;
use App\Models\Masters\CustomerStatus;

class InitCustomerStatusesSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $model = new CustomerStatus();
        $model->id=2;
        $model->name="本会員";
        $model->rank=0;
        $model->save();

        $model = new CustomerStatus();
        $model->id=3;
        $model->name="一般会員";
        $model->rank=1;
        $model->save();

    }
}
