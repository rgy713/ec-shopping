<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductPurchaseWarningsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_purchase_warnings', function (Blueprint $table) {
            $table->increments('id');
            $table->smallInteger('product_id');
            $table->smallInteger('product_id_to_warn');
            $table->smallInteger('purchase_warning_type_id');
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products')->onDelete("CASCADE");
            $table->foreign('product_id_to_warn')->references('id')->on('products')->onDelete("CASCADE");
            $table->foreign('purchase_warning_type_id')->references('id')->on('purchase_warning_types')->onDelete("CASCADE");

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_purchase_warnings');
    }
}
