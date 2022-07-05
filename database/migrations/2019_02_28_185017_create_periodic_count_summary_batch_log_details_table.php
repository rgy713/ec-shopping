<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePeriodicCountSummaryBatchLogDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('periodic_count_summary_batch_log_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('periodic_count_summary_batch_log_id');//親集計ID
            $table->integer('item_lineup_id');//商品ラインナップID
            $table->integer('active_count');//ラインナップ毎の稼働件数
            $table->integer('stop_count');//ラインナップ毎の停止件数

            $table->foreign('item_lineup_id')->references('id')->on('item_lineups')->onDelete("CASCADE");
            $table->foreign('periodic_count_summary_batch_log_id')->references('id')->on('periodic_count_summary_batch_logs')->onDelete("CASCADE");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('periodic_count_summary_batch_log_details');
    }
}
