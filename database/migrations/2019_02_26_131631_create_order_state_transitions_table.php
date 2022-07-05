<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderStateTransitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_state_transitions', function (Blueprint $table) {
            $table->smallInteger('id')->primary();
            $table->smallInteger('status_id_from');//状態遷移元のステータスID：0は初期状態を示す
            $table->smallInteger('status_id_to');//状態遷移先のステータスID
            $table->boolean('permission');//許可：trueの場合、遷移して良い。
            $table->smallInteger('rank');//とりあえずつけてあるが、特に用途が無い。
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_state_transitions');
    }
}
