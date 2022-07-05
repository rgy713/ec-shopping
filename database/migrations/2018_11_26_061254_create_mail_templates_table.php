<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMailTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mail_templates', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->text("name");
            $table->smallInteger("mail_layout_id");
            $table->text("subject");
            $table->text("body_file_path");
            $table->smallInteger("mail_template_type_id");
            $table->text("sending_trigger");
            $table->timestamps();

            $table->foreign('mail_layout_id')->references('id')->on('mail_layouts')->onDelete("RESTRICT");
            $table->foreign('mail_template_type_id')->references('id')->on('mail_template_types')->onDelete("RESTRICT");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mail_templates');
    }
}
