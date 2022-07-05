<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerPairRelationshipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_pair_relationships', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("customer_id_a");//顧客IDの組み合わせの一方
            $table->integer("customer_id_b");//顧客IDの組み合わせの一方
            $table->smallInteger("customer_pair_relationship_type_id");//関係の種類を示すID
            $table->timestamps();

            $table->foreign('customer_id_a')->references('id')->on('customers')->onDelete("CASCADE");
            $table->foreign('customer_id_b')->references('id')->on('customers')->onDelete("CASCADE");
            $table->foreign('customer_pair_relationship_type_id')->references('id')->on('customer_pair_relationship_types')->onDelete("RESTRICT");

            $table->unique(['customer_id_a','customer_id_b']);

        });

        //2点の組み合わせ情報を持つため、A < B の制約を追加
        DB::statement("ALTER TABLE customer_pair_relationships ADD CHECK (customer_id_a < customer_id_b);");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_pair_relationships');
    }
}
