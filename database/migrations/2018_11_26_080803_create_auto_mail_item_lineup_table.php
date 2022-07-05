<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAutoMailItemLineupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auto_mail_item_lineup', function (Blueprint $table) {
            $table->increments('id');
            $table->smallInteger("auto_mail_setting_id");
            $table->smallInteger("item_lineup_id");
            $table->timestamps();

            $table->foreign('auto_mail_setting_id')->references('id')->on('auto_mail_settings')->onDelete("CASCADE");
            $table->foreign('item_lineup_id')->references('id')->on('item_lineups')->onDelete("RESTRICT");

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('auto_mail_item_lineup');
    }
}
