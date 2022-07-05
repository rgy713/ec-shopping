<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAutoMailSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auto_mail_settings', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->smallInteger("mail_template_id");
            $table->smallInteger("order_method");
            $table->smallInteger("elapsed_days");
            $table->boolean("enabled")->default(false);
            $table->boolean("regular_member_only_flag")->default(false);
            $table->boolean("first_purchase_only_flag")->default(false);
            $table->boolean("customer_mail_magazine_flag")->default(true);
            $table->timestamps();

            $table->foreign('mail_template_id')->references('id')->on('mail_templates')->onDelete("RESTRICT");
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('auto_mail_settings');
    }
}
