<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShippingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shippings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("order_id");
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

            $table->smallInteger("delivery_id");
            $table->text("delivery_name");
            $table->smallInteger("delivery_time_id")->nullable();
            $table->text("delivery_time_name")->nullable();
            //伝票番号
            $table->text("delivery_slip_num")->nullable();

            //配送希望日
            $table->date("requested_delivery_date")->nullable();
            //発送予定日
            $table->date("scheduled_shipping_date")->nullable();
            //到着予定日
            $table->date("estimated_arrival_date")->nullable();
            //発送日時
            $table->timestamp("shipped_timestamp")->nullable();

            $table->timestamps();

            //外部キー
            $table->foreign('order_id')->references('id')->on('orders')->onDelete("CASCADE");
            $table->foreign('prefecture_id')->references('id')->on('prefectures')->onDelete("RESTRICT");
            $table->foreign('delivery_id')->references('id')->on('deliveries')->onDelete("RESTRICT");
            $table->foreign('delivery_time_id')->references('id')->on('delivery_times')->onDelete("RESTRICT");

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shippings');
    }
}
