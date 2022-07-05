<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPaymentMethodsAddSettlementColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_methods', function (Blueprint $table) {
            //決済関連列を一旦削除
            $table->text("settlement_vendor")->nullable();
            $table->text("settlement_vendor_sub_code")->nullable();
        });

        if(config("database.default")==="pgsql") {
            DB::statement("COMMENT ON COLUMN payment_methods.settlement_vendor IS '決済ベンダ:支払い方法を実際に処理するベンダの名称を保存する。';");
            DB::statement("COMMENT ON COLUMN payment_methods.settlement_vendor_sub_code IS 'ベンダ側カードID:旧システムdtb_order.quick_memo 内の、CardSeq相当の情報を保存。決済ベンダ側で発行されるカード番号を特定するためのIDを保存する。定期バッチ処理による決済時に利用される。カード情報お預かりサービスのドキュメントを参照のこと。';");
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_methods', function (Blueprint $table) {
            //決済関連列を一旦削除
            $table->dropColumn("settlement_vendor");
            $table->dropColumn("settlement_vendor_sub_code");
        });
    }
}
