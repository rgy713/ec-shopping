<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPeriodicOrdersAddSettlementColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('periodic_orders', function (Blueprint $table) {
            $table->text("settlement_card_id")->nullable();//決済に使用するカード番号（ベンダ側のID）
        });

        if(config("database.default")==="pgsql") {
            DB::statement("COMMENT ON COLUMN periodic_orders.settlement_card_id IS 'ベンダ側カードID:旧システムquick_memo 内の、CardSeq相当。決済ベンダ側で発行されるカード番号を特定するためのIDを保存する。カード情報お預かりサービスのドキュメントを参照のこと。';");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('periodic_orders', function (Blueprint $table) {
            $table->dropColumn("settlement_card_id");
        });

    }
}
