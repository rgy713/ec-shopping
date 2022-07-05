<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePeriodicShippingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('periodic_shippings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("periodic_order_id");
            $table->text('name01');
            $table->text('name02');
            $table->text('kana01');
            $table->text('kana02');
            $table->string('zipcode',7);
            $table->smallInteger('prefecture_id');
            $table->text('address01');
            $table->text('address02');
            $table->text('requests_for_delivery')->nullable();
            $table->text('email')->nullable();
            $table->text('phone_number01');
            $table->text('phone_number02')->nullable();

            //列名変更
            $table->smallInteger("last_delivery_id");
            //smallint型のdelivery_time_idから、time型のdelivery_timeに変更
            //定期バッチ処理時、この時刻を見て都度time_idを判断
            $table->time("requested_delivery_time")->nullable();
            $table->timestamps();

            //外部キー
            $table->foreign('periodic_order_id')->references('id')->on('periodic_orders')->onDelete("CASCADE");
            $table->foreign('prefecture_id')->references('id')->on('prefectures')->onDelete("RESTRICT");
            $table->foreign('last_delivery_id')->references('id')->on('deliveries')->onDelete("RESTRICT");


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('periodic_shippings');
    }
}
