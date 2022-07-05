<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAspMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asp_media', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->text('name');
            $table->smallInteger('default_item_lineup_id');
            $table->integer('default_cost')->default(0);
            $table->text("remark1")->nullable();
            $table->boolean("enabled")->default(true);

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
        Schema::dropIfExists('asp_media');
    }
}
