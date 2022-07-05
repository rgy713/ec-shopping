<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomersTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('customers', function (Blueprint $table) {
            $table->increments("id");
            $table->text('name01');
            $table->text('name02');
            $table->text('kana01');
            $table->text('kana02');
            $table->string('zipcode',7);
            $table->smallInteger('prefecture_id');
            $table->text('address01');
            $table->text('address02');
            $table->text('requests_for_delivery')->nullable();
            $table->text('email')->nullable();
            $table->text('phone_number01');
            $table->text('phone_number02')->nullable();
            $table->date('birthday')->nullable();
            $table->text('password')->nullable();
            $table->timestamp('first_buy_date')->nullable();
            $table->timestamp('last_buy_date')->nullable();
            $table->smallInteger('buy_times')->default(0);
            $table->integer('buy_total')->default(0);
            $table->smallInteger('buy_volume')->default(0);
            $table->boolean('no_phone_call_flag')->default(false);
            $table->boolean('mail_magazine_flag')->default(false);
            $table->boolean('dm_flag')->default(false);
            $table->boolean('wholesale_flag')->default(false);
            $table->smallInteger('pfm_status_id')->nullable();
            $table->integer('advertising_media_code')->nullable();
            $table->smallInteger('registration_route_id')->nullable();
            $table->smallInteger('customer_status_id');

            //登録確定日時。一般顧客でかつクレカ購入の場合、決済完了までNULL、決済完了時にtimestampを保存する
            $table->timestamp("confirmed_timestamp")->nullable();

            $table->timestamps();
            //論理削除フラグ
            $table->softDeletes();

            //簡易検索で利用される条件
            $table->index("phone_number01");
            $table->index("phone_number02");
            $table->index("email");

            $table->foreign('pfm_status_id')->references('id')->on('pfm_statuses');
            $table->foreign('advertising_media_code')->references('code')->on('advertising_media')->onDelete('RESTRICT')->onUpdate('RESTRICT');
            $table->foreign('registration_route_id')->references('id')->on('registration_routes');
            $table->foreign('customer_status_id')->references('id')->on('customer_statuses');

        }
        );

        //式インデクス、postgresql文法に依存するため条件分岐
        if(config("database.default")==="pgsql"){
            //簡易検索画面での検索条件となる、名前、カナ
            DB::statement('CREATE INDEX customers_name_idx ON customers ((name01 || name02));' );
            DB::statement('CREATE INDEX customers_kana_idx ON customers ((kana01 || kana02));' );
        }



    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('customers');
    }
}
