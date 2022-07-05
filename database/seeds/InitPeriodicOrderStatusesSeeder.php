<?php

use Illuminate\Database\Seeder;

class InitPeriodicOrderStatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $model=new \App\Models\Masters\PeriodicOrderStatus();
        $model->id=1;
        $model->name="正常";
        $model->rank=1;
        $model->save();

        $model=new \App\Models\Masters\PeriodicOrderStatus();
        $model->id=2;
        $model->name="失敗";
        $model->rank=2;
        $model->save();
    }
}
