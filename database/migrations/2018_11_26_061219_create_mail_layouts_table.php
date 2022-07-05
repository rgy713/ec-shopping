<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMailLayoutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mail_layouts', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->text("name");
            $table->text("remark")->nullabel();
            $table->text("header_file_path");
            $table->text("footer_file_path");
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
        Schema::dropIfExists('mail_layouts');
    }
}
