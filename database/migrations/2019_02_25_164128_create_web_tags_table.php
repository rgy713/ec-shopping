<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWebTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('web_tags', function (Blueprint $table) {
            $table->increments('id');
            $table->text('name');//タグ名：管理者識別用
            $table->text('vendor');//ベンダー名：管理者識別用
            $table->smallInteger('creator_id');//作成した管理者のadmin_id：後々、代理店にログイン権限を付与して運用することを想定。（作成者のみ閲覧可、などの権限/制限を設ける想定）
            $table->boolean('enabled');//ベンダー名：管理者識別用
            $table->smallInteger('tag_position_id');//タグ設置位置ID
//            $table->smallInteger('tag_device_id');//表示対象デバイスID
            $table->integer('rank');//表示優先順位
            $table->text('remark');//備考：管理者用メモ
            $table->text('content');//タグ内容：bladeテンプレートに出力される内容
            $table->timestamps();

            $table->foreign('creator_id')->references('id')->on('admins')->onDelete("restrict");
            $table->foreign('tag_position_id')->references('id')->on('tag_positions')->onDelete("restrict");
            $table->index('rank');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('web_tags');
    }
}
