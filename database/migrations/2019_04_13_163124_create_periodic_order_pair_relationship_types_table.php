<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePeriodicOrderPairRelationshipTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('periodic_order_pair_relationship_types', function (Blueprint $table) {
            $table->smallInteger('id')->primary();
            $table->text('name');
            $table->text('description');
            $table->smallInteger('rank');
            $table->timestamps();
        });

        if(config("database.default")==="pgsql") {
            DB::statement("COMMENT ON TABLE periodic_order_pair_relationship_types IS '定期の組の種別（重複疑い、統合済である、等）を持つ';");
            DB::statement("COMMENT ON COLUMN periodic_order_pair_relationship_types.id IS '定期組種別ID:主キー';");
            DB::statement("COMMENT ON COLUMN periodic_order_pair_relationship_types.name IS '定期組種別名:種別の名称。管理画面、ユーザー画面で使われる想定なし。システム開発者が識別できる名称を保存する。';");
            DB::statement("COMMENT ON COLUMN periodic_order_pair_relationship_types.description IS '定期組説明:説明文。システム開発者がレコード内容を理解できるような説明を保存する。';");
            DB::statement("COMMENT ON COLUMN periodic_order_pair_relationship_types.rank IS '定期組並び順:マスタテーブル用のrank。昇順で評価';");
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('periodic_order_pair_relationship_types');
    }
}
