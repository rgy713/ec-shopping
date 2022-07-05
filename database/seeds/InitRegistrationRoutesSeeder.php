<?php

use Illuminate\Database\Seeder;
use App\Models\Masters\RegistrationRoute;

class InitRegistrationRoutesSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $model = new RegistrationRoute();
        $model->id=1;
        $model->name="web";
        $model->rank=1;
        $model->save();

        $model = new RegistrationRoute();
        $model->id=2;
        $model->name="inbound";
        $model->rank=2;
        $model->save();
    }
}
