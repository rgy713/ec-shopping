<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStepdmHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stepdm_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamp("executed_timestamp");
            $table->integer("total_count")->nullable();
            $table->timestamp("finished_timestamp")->nullable();
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
        Schema::dropIfExists('stepdm_histories');
    }
}
