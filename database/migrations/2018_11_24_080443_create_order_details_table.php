<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->increments('id');
            $table->smallInteger("order_id");
            $table->integer("product_id");
            $table->text("product_name");
            $table->text("product_code");
            $table->integer("price");
            $table->integer("tax");
            $table->integer("catalog_price");
            $table->integer("catalog_price_tax");
            //数量
            $table->smallInteger("quantity");
            //本数
            $table->smallInteger("volume");

            //外部キー
            $table->foreign('order_id')->references('id')->on('orders')->onDelete("CASCADE");
            $table->foreign('product_id')->references('id')->on('products')->onDelete("RESTRICT");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_details');
    }
}
