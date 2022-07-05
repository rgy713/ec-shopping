<?php

use Illuminate\Database\Seeder;
use App\Models\Masters\FixedPeriodicInterval;
use App\Models\Product;

class InitFixedPeriodicIntervalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $fixedPeriodicInterval = new FixedPeriodicInterval();
        //日数間隔
        $fixedPeriodicInterval->periodic_interval_type_id=1;
        //60日
        $fixedPeriodicInterval->interval_days=60;

        $fixedPeriodicInterval->save();


        //定期間隔固定情報の投入
        $product=Product::find(94);
        $product->fixed_periodic_interval_id=$fixedPeriodicInterval->id;
        $product->save();

        $product=Product::find(95);
        $product->fixed_periodic_interval_id=$fixedPeriodicInterval->id;
        $product->save();

        $product=Product::find(159);
        $product->fixed_periodic_interval_id=$fixedPeriodicInterval->id;
        $product->save();

        $product=Product::find(160);
        $product->fixed_periodic_interval_id=$fixedPeriodicInterval->id;
        $product->save();

    }
}
