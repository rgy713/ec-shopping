<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAdminsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('admins', function (Blueprint $table) {
            $table->boolean('option_left_menu')->default(true);
            $table->boolean('option_right_menu')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn('option_left_menu');
            $table->dropColumn('option_right_menu');
        });
    }
}
