<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderBundleShippingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_bundle_shippings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id');
            $table->integer('parent_order_id');
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete("CASCADE");
            $table->foreign('parent_order_id')->references('id')->on('orders')->onDelete("CASCADE");

            $table->unique('order_id');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_bundle_shippings');
    }
}
