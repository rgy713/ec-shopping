<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTablePeriodicOrdersAddColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('periodic_orders', function (Blueprint $table) {
            $table->text("settlement_masked_card_number")->nullable();
            $table->boolean("duplication_warning_flag")->default(false);//重複警告フラグ
        });

        if(config("database.default")==="pgsql") {
            DB::statement("COMMENT ON COLUMN periodic_orders.settlement_masked_card_number IS '決済用のカード番号下4桁表示用。027応答電文のcard_numberを保存する';");
            DB::statement("COMMENT ON COLUMN periodic_orders.duplication_warning_flag IS '重複定期警告:同一の購入者によって、同一ラインナップの定期が作成されている疑いがある場合にtrue。顧客登録時に判定され更新される。詳細な情報はperiodic_order_pair_relationshipsテーブルに保持。 ';");
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
            $table->dropColumn("settlement_masked_card_number");
            $table->dropColumn("duplication_warning_flag");
        });

    }
}
