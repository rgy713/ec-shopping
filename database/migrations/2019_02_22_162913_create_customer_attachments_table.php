<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerAttachmentsTable extends Migration
{
    /**
     * 顧客添付ファイルテーブル
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_attachments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('customer_id');
            $table->text('title');
            $table->text('file_path');
            $table->timestamps();

            //外部キー
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete("CASCADE");
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
        Schema::dropIfExists('customer_attachments');
    }
}
