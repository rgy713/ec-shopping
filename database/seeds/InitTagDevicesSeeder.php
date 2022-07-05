<?php

use Illuminate\Database\Seeder;

class InitTagDevicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $model=new \App\Models\Masters\TagDevice();
        $model->id=1;
        $model->name='pc';
        $model->rank=1;
        $model->save();

        $model=new \App\Models\Masters\TagDevice();
        $model->id=2;
        $model->name='sp';
        $model->rank=2;
        $model->save();

    }
}
