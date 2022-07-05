<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_pages', function (Blueprint $table) {
            $table->smallInteger('id')->primary();
            $table->text("name");//管理者識別のための名称
            $table->text("group");//管理者識別のための名称2(そのうちマスタにするかも？)
            $table->smallInteger("rank");//管理画面内での表示順を想定
            $table->boolean("pc")->default(true);//タグ設置設定時のpcチェックボックス表示フラグ
            $table->boolean("sp")->default(true);//タグ設置設定時のspチェックボックス表示フラグ
            $table->text("route_name");//ルーティング名
            $table->text("path");//管理者識別のためのURL文字列

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
        Schema::dropIfExists('user_pages');
    }
}
