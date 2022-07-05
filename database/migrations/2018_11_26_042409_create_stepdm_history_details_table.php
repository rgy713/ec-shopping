<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStepdmHistoryDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stepdm_history_details', function (Blueprint $table) {
            $table->increments('id');

            $table->integer("stepdm_history_id");
            $table->integer("order_id");
            $table->smallInteger("stepdm_setting_id");
            $table->text("stepdm_setting_code");

            $table->text('order_name01');
            $table->text('order_name02');
            $table->text('order_kana01');
            $table->text('order_kana02');
            $table->string('order_zipcode',7);
            $table->smallInteger('order_prefecture_id');
            $table->text('order_address01');
            $table->text('order_address02');
            $table->text('order_phone_number01');

            $table->timestamps();

            $table->foreign('stepdm_history_id')->references('id')->on('stepdm_histories')->onDelete("CASCADE");
            $table->foreign('order_id')->references('id')->on('orders')->onDelete("RESTRICT");
            $table->foreign('stepdm_setting_id')->references('id')->on('orders')->onDelete("RESTRICT");
            $table->foreign('order_prefecture_id')->references('id')->on('prefectures')->onDelete("RESTRICT");


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stepdm_history_details');
    }
}
