<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableCsvTypesAddColumnConfigurable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('csv_types', function (Blueprint $table) {
            $table->boolean("configurable")->default(false);
        });

        if(config("database.default")==="pgsql") {
            DB::statement("COMMENT ON COLUMN csv_types.configurable IS '設定可能フラグ:管理画面で出力項目を制御可能なCSVはTRUE';");
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('csv_types', function (Blueprint $table) {
            $table->dropColumn("configurable");
        });

    }
}
