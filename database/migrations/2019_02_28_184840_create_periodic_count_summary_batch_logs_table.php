<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePeriodicCountSummaryBatchLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('periodic_count_summary_batch_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('active_count');//定期全体の稼働件数
            $table->integer('stop_count');//定期全体の停止件数

            $table->timestamp('created_at');//バッチ実行日時
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('periodic_count_summary_batch_logs');
    }
}
