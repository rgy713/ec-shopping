<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableCustomersAddColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->boolean("sub_account_warning_flag")->default(false);//重複警告フラグ
        });

        if(config("database.default")==="pgsql") {
            DB::statement("COMMENT ON COLUMN customers.sub_account_warning_flag IS 'サブアカウント警告:同一人物によって複数のアカウントが作成されている疑いがある場合にtrue。顧客登録時に判定され更新される。詳細な情報はcustomer_pair_relationshipsテーブルに保持。 ';");
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn("sub_account_warning_flag");
        });

    }
}
