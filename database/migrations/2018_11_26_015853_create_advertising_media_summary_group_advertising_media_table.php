<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdvertisingMediaSummaryGroupAdvertisingMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advertising_media_summary_group_advertising_media', function (Blueprint $table) {
            $table->increments('id');
            $table->smallInteger("advertising_media_summary_group_id");
            $table->integer("advertising_medium_id");
            $table->timestamps();

            $table->foreign('advertising_media_summary_group_id','media_groups_foreign_1')->references('id')->on('advertising_media_summary_groups')->onDelete("CASCADE");
            $table->foreign('advertising_medium_id','media_groups_foreign_2')->references('id')->on('advertising_media')->onDelete("CASCADE");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('advertising_media_summary_group_advertising_media');
    }
}
