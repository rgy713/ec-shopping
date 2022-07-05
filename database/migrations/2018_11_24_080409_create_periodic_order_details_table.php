<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePeriodicOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('periodic_order_details', function (Blueprint $table) {
            $table->increments('id');
            $table->smallInteger("periodic_order_id");
            $table->integer("product_id");
            $table->text("product_name");
            $table->text("product_code");

            //価格情報は受注データ作成時点で決定し、order_detailsに保存する。定期詳細には保持しない。
//            $table->integer("price");
//            $table->integer("tax");
//            $table->integer("catalog_price");
//            $table->integer("catalog_price_tax");

            //数量
            $table->smallInteger("quantity");
            //本数
            $table->smallInteger("volume");

            //外部キー
            $table->foreign('periodic_order_id')->references('id')->on('periodic_orders')->onDelete("CASCADE");
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
        Schema::dropIfExists('periodic_order_details');
    }
}
