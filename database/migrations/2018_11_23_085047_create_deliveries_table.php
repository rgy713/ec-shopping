<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeliveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->smallInteger('id')->primary();
            $table->smallInteger("product_delivery_type_id");
            $table->text("name");
            $table->text("service_name");
            $table->text("remark")->nullabel();
            $table->text("confirm_url");
            $table->boolean("user_visible");
            $table->smallInteger("rank");
            $table->timestamps();


            $table->foreign('product_delivery_type_id')->references('id')->on('product_delivery_types')->onDelete("RESTRICT");




        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deliveries');
    }
}
