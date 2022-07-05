<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeliveryLeadtimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_leadtimes', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->smallInteger("delivery_id");
            $table->smallInteger("prefecture_id");
            $table->smallInteger("days");
            $table->text("remark");
            $table->timestamps();

            //組み合わせはユニーク
            $table->unique(['delivery_id','prefecture_id']);

            $table->foreign('delivery_id')->references('id')->on('deliveries')->onDelete("RESTRICT");
            $table->foreign('prefecture_id')->references('id')->on('prefectures')->onDelete("RESTRICT");

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('delivery_leadtimes');
    }
}
