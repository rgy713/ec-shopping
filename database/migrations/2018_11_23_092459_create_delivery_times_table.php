<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeliveryTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_times', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->smallInteger("delivery_id");
            $table->text("delivery_time");
            $table->time("time_range_from");//時間帯の開始時刻：定期バッチ処理時、periodic_shippings.delivery_time と比較する意図で持たせている
            $table->time("time_range_to");//時間帯の終端時刻：定期バッチ処理時、periodic_shippings.delivery_time と比較する意図で持たせている
            $table->smallInteger('rank');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('delivery_times');
    }
}
