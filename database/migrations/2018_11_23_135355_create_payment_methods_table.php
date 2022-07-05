<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->smallInteger('id')->primary();
            $table->text("name");
            $table->integer("fee");
            $table->smallInteger("rank");
            $table->text("remark")->nullable();
            $table->integer("lower_limit")->default(0);
            $table->integer("upper_limit")->nullable();
            $table->boolean("user_visible")->default(false);
            $table->boolean("admin_visible")->default(false);

            $table->smallInteger("initial_order_status_id");//通常受注の初期ステータスID
            $table->smallInteger("initial_periodic_batch_order_status_id");//定期バッチ処理時の初期受注ステータスID

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('initial_order_status_id')->references('id')->on('order_statuses')->onDelete("RESTRICT");
            $table->foreign('initial_periodic_batch_order_status_id')->references('id')->on('order_statuses')->onDelete("RESTRICT");

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_methods');
    }
}
