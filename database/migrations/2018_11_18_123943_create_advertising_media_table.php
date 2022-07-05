<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdvertisingMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advertising_media', function (Blueprint $table) {

            $table->increments('id');
            $table->integer("code")->unique();
            $table->text("name");
            $table->smallInteger("media_type_id");
            $table->text("area")->nullable();
            $table->date("date");
            $table->text("start_time")->nullable();
            $table->integer("cost");
            $table->smallInteger("item_lineup_id");
            $table->text("remark")->nullable();
            $table->smallInteger("creator_admin_id");
            $table->integer("circulation")->nullable();
            $table->text("detail")->nullable();
            $table->text("broadcaster")->nullable();
            $table->smallInteger("broadcast_minutes")->nullable();
            $table->time("broadcast_duration_from")->nullable();
            $table->time("broadcast_duration_to")->nullable();
            $table->smallInteger("call_expected")->nullable();
            $table->boolean("asp_flag")->default(false);
            $table->text("asp_name")->nullable();
            $table->timestamp("asp_from")->nullable();
            $table->timestamp("asp_to")->nullable();
            $table->timestamps();

            //インデクス
            $table->index('name');
            $table->index('code');
            $table->index('date');

            //外部キー
            $table->foreign('media_type_id')->references('id')->on('medium_types'); //外部キー参照
            $table->foreign('item_lineup_id')->references('id')->on('item_lineups'); //外部キー参照
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('advertising_media');
    }
}
