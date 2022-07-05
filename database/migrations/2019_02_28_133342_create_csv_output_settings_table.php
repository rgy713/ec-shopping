<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCsvOutputSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('csv_output_settings', function (Blueprint $table) {
            $table->smallInteger('id')->primary();
            $table->smallInteger('csv_type_id');

            //Select定義を格納する 例：orders.id , customers.name01 , (SELECT name FROM mtb_pref WHERE mtb_pref.id = dtb_order.order_pref) as pref
            $table->text('col');
            $table->text('item_name');//旧DB disp_name：画面表示、CSVファイルヘッダに出力する文字列
            $table->smallInteger('rank');
            $table->boolean('enabled');//trueの場合、CSVに出力
            $table->timestamps();

            $table->foreign('csv_type_id')->references('id')->on('csv_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('csv_output_settings');
    }
}
