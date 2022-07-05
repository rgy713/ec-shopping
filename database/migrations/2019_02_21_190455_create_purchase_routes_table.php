<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_routes', function (Blueprint $table) {
            $table->smallInteger('id')->primary();
            $table->text('name');
            $table->smallInteger('rank');
            $table->timestamps();
        });

        //TODO:orders.purchase_route_idに参照整合性制約を追加
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_routes');
    }
}
