<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopMemosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_memos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('customer_id');
            $table->integer('order_id')->nullable();
            $table->integer('periodic_order_id')->nullable();
            //作成者のadmin_id
            $table->smallInteger('created_by');
            //削除者のadmin_id
            $table->smallInteger('deleted_by')->nullable();;
            //memo内容
            $table->text('note');
            //重要フラグ
            $table->boolean('important')->default(false);
            //クレームフラグ
            $table->boolean('claim_flag')->default(false);
            $table->timestamps();

            //外部キー
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete("CASCADE");
            //インデクス、現行テーブルと同様
            $table->index(["customer_id","created_at"]);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shop_memos');
    }
}
