<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableOrdersAddColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            //決済に関する情報列の追加
            $table->text("settlement_masked_card_number")->nullable();//決済したカードの下4桁表示用、応答電文のmasked_card_numberを保存する
        });

        if(config("database.default")==="pgsql") {
            DB::statement("COMMENT ON COLUMN orders.settlement_masked_card_number IS '決済したカードの下4桁表示用、応答電文のmasked_card_numberを保存する';");
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn("settlement_masked_card_number");
        });
    }
}
