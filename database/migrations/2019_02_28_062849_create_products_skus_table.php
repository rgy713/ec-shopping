<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsSkusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products_skus', function (Blueprint $table) {
            $table->increments('id');//サロゲートキー
            $table->smallInteger('product_id');
            $table->smallInteger('stock_keeping_unit_id');
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products')->onDelete("cascade");
            $table->foreign('stock_keeping_unit_id')->references('id')->on('stock_keeping_units')->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products_skus');
    }
}
