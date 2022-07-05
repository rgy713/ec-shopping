<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWebTagsUserPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('web_tags_user_pages', function (Blueprint $table) {
            $table->increments('id');//サロゲートキー
            $table->integer('web_tag_id');//設置タグ
            $table->integer('user_page_id');//設置対象ページ
            $table->integer('tag_device_id');//表示対象デバイス
            $table->timestamps();

            //3列の組み合わせが一意
            $table->unique(['web_tag_id','user_page_id','tag_device_id']);
            $table->foreign('web_tag_id')->references('id')->on('web_tags')->onDelete("cascade");//タグ削除時は、表示設定そのものも削除
            $table->foreign('user_page_id')->references('id')->on('user_pages')->onDelete("restrict");//マスタ削除は異常事態なので禁止
            $table->foreign('tag_device_id')->references('id')->on('tag_devices')->onDelete("restrict");//マスタ削除は異常事態なので禁止

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('web_tags_user_pages');
    }
}
