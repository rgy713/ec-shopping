<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePeriodicOrderPairRelationshipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('periodic_order_pair_relationships', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("periodic_order_id_a");//定期IDの組み合わせの一方
            $table->integer("periodic_order_id_b");//定期IDの組み合わせの一方
            $table->smallInteger("periodic_order_pair_relationship_type_id");//関係の種類を示すID
            $table->timestamps();

            $table->foreign('periodic_order_id_a')->references('id')->on('periodic_orders')->onDelete("CASCADE");
            $table->foreign('periodic_order_id_b')->references('id')->on('periodic_orders')->onDelete("CASCADE");
            $table->foreign('periodic_order_pair_relationship_type_id')->references('id')->on('periodic_order_pair_relationship_types')->onDelete("RESTRICT");

            $table->unique(['periodic_order_id_a','periodic_order_id_b']);
        });

        //2点の組み合わせ情報を持つため、A < B の制約を追加
        DB::statement("ALTER TABLE periodic_order_pair_relationships ADD CHECK (periodic_order_id_a < periodic_order_id_b);");

        if(config("database.default")==="pgsql") {
            DB::statement("COMMENT ON TABLE periodic_order_pair_relationships IS '定期ID組み合わせテーブル:定期IDの組み合わせと、その組み合わせがもつ関係（重複している疑いあり、など）の情報を持つ。';");
            DB::statement("COMMENT ON COLUMN periodic_order_pair_relationships.id IS 'ID:サロゲートキー';");
            DB::statement("COMMENT ON COLUMN periodic_order_pair_relationships.periodic_order_id_a IS '定期ID-A:定期IDの組み合わせの片側。CHECK制約により若い方の定期IDをAに保存しないとエラーとなる。';");
            DB::statement("COMMENT ON COLUMN periodic_order_pair_relationships.periodic_order_id_b IS '定期ID-B:定期IDの組み合わせの片側。CHECK制約により若い方の定期IDをAに保存しないとエラーとなる。';");
            DB::statement("COMMENT ON COLUMN periodic_order_pair_relationships.periodic_order_pair_relationship_type_id IS '関係種別ID:定期ID-Aと定期ID-Bの関係を示す。';");
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('periodic_order_pair_relationships');
    }
}
