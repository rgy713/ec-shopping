<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMarketingSummaryBatchLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marketing_summary_batch_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamp('created_at');
            $table->date('period_from');//集計対象期間の開始
            $table->date('period_to');//集計対象期間の終端
            $table->integer('sales_of_first');//「新規」の売上
            $table->integer('sales_of_repeat');//「リピート」の売上
            $table->integer('sales');//売上
            $table->integer('customer_count');//顧客数
            $table->integer('order_count');//受注数
            $table->integer('average_unit_price');//平均単価
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('marketing_summary_batch_logs');
    }
}
