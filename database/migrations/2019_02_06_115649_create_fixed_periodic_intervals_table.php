<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFixedPeriodicIntervalsTable extends Migration
{
    /**
     * Run the migrations.
     * 固定の定期間隔情報
     * @return void
     */
    public function up()
    {
        Schema::create('fixed_periodic_intervals', function (Blueprint $table) {
            $table->smallIncrements('id');

            //
            $table->smallInteger("periodic_interval_type_id");
            $table->smallInteger("interval_days")->nullable();
            $table->smallInteger("interval_month")->nullable();
            $table->smallInteger("interval_date_of_month")->nullable();

            $table->timestamps();

            if(config("database.default")==="pgsql"){
                /**
                 * チェック制約：定期テーブルにおけるチェック制約と同等。
                 */
                DB::statement("ALTER TABLE periodic_orders ADD CHECK ((periodic_interval_type_id = 1 AND interval_days > 0) <> (periodic_interval_type_id = 2 AND interval_month > 0 AND interval_date_of_month > 0));");
            }

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fixed_periodic_intervals');
    }
}
