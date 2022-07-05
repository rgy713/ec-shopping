<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStepdmSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stepdm_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->text("code");
            $table->smallInteger("product_id");
            $table->smallInteger("req_periodic_count")->nullable();
            $table->smallInteger("req_elapsed_days_from_sending_out");
            $table->smallInteger("stepdm_type_id");
            $table->boolean("is_active");
            $table->timestamps();

            //外部キー
            $table->foreign('product_id')->references('id')->on('products')->onDelete('CASCADE');
            $table->foreign('stepdm_type_id')->references('id')->on('stepdm_types')->onDelete('RESTRICT');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stepdm_settings');
    }
}
