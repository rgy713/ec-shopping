<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemBundleSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_bundle_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->smallInteger('product_id');
            $table->smallInteger('quantity');
            $table->smallInteger('req_product_id');
            $table->smallInteger('req_periodic_count');
            $table->boolean("enabled")->default(true);
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products')->onDelete("CASCADE");
            $table->foreign('req_product_id')->references('id')->on('products')->onDelete("CASCADE");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_bundle_settings');
    }
}
