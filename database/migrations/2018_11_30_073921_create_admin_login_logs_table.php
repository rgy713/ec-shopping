<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminLoginLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //「認証の試行」を格納する。認証成功時、state = true、認証失敗時、state = false
        Schema::create('admin_login_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('account');//ログイン試行時の入力アカウント名
            $table->boolean("state");//成功か失敗
            $table->text('host');//ログイン試行者のホストアドレス
            $table->text('user_agent');//ログイン試行者のユーザーエージェント
            $table->timestamp('created_at');//ログイン試行日時
            $table->timestamp('logged_out_at')->nullable();//ログアウト日時
            //update_at は不要

            //ログイン成功時、ログインしたアカウントのIDを格納
            $table->smallInteger('admin_id')->nullable();

            $table->foreign('admin_id')->references('id')->on('admins')->onDelete("CASCADE");

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_login_logs');
    }
}
