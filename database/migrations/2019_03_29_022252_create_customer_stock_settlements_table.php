<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerStockSettlementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_stock_settlements', function (Blueprint $table) {
            $table->bigIncrements('id');//サロゲートキー
            $table->integer('customer_id');
            $table->text('vendor');//決済ベンダコード（当面paygentのみ格納予定）
            $table->text('vendor_sub_code')->nullable();
            $table->boolean('has_stock');//カード情報をベンダ側に持っている場合true

            $table->unique(['customer_id','vendor','vendor_sub_code','has_stock']);
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
        });



        if(config("database.default")==="pgsql") {
            DB::statement("COMMENT ON TABLE customer_stock_settlements IS 'ペイジェントのカード番号お預かり機能により、カード情報をペイジェントサーバに保存しているかどうかを情報として持つ。paygent以外の決済方法を追加することを想定して、決済ベンダ等の情報とセットで、1顧客に対して複数保持できる構造としている。';");
            DB::statement("COMMENT ON COLUMN customer_stock_settlements.customer_id IS '顧客ID:';");
            DB::statement("COMMENT ON COLUMN customer_stock_settlements.vendor IS 'ベンダコード:当面は「paygent」以外の値は保存されない予定';");
            DB::statement("COMMENT ON COLUMN customer_stock_settlements.vendor_sub_code IS 'ベンダサブコード:当面は「credit」（paygentのクレジットカード）以外の値は保存されない予定';");
            DB::statement("COMMENT ON COLUMN customer_stock_settlements.has_stock IS 'フラグ:カード情報を預けている場合true。カード情報削除電文を送信し、0件となった場合はfalse';");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_stock_settlements');
    }
}
