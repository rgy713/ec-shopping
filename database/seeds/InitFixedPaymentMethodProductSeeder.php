<?php

use Illuminate\Database\Seeder;
use App\Models\Masters\FixedPeriodicInterval;
use App\Models\Product;

class InitFixedPaymentMethodProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        //支払い方法固定情報の投入
        $product=Product::find(94);
        $product->fixed_payment_method_id=5;
        $product->save();

        $product=Product::find(95);
        $product->fixed_payment_method_id=4;
        $product->save();

        $product=Product::find(155);
        $product->fixed_payment_method_id=5;
        $product->save();

        $product=Product::find(156);
        $product->fixed_payment_method_id=4;
        $product->save();

        $product=Product::find(159);
        $product->fixed_payment_method_id=5;
        $product->save();

        $product=Product::find(160);
        $product->fixed_payment_method_id=4;
        $product->save();

    }
}
