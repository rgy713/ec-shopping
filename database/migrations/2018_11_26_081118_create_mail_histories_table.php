<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMailHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mail_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("customer_id");
            $table->integer("order_id")->nullable();
            $table->smallInteger("mail_template_id");
            $table->text("subject");
            $table->text("body");
            $table->smallInteger('auto_mail_settings_id')->nullable();
            $table->timestamps();


            $table->foreign('customer_id')->references('id')->on('customers')->onDelete("RESTRICT");
            $table->foreign('order_id')->references('id')->on('orders')->onDelete("RESTRICT");
            $table->foreign('mail_template_id')->references('id')->on('mail_templates')->onDelete("RESTRICT");

            $table->index(['customer_id','mail_template_id','auto_mail_settings_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mail_histories');
    }
}
