<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePeriodicOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('periodic_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('customer_id');
            $table->text('name01');
            $table->text('name02');
            $table->text('kana01');
            $table->text('kana02');
            $table->string('zipcode',7);
            $table->smallInteger('prefecture_id');
            $table->text('address01');
            $table->text('address02');
            $table->text('requests_for_delivery')->nullable();
            //顧客テーブルのemail列がNull可となった（管理画面からの登録の場合、メールアドレスなしでの登録可とした）ため、定期データのemailもnull可に変更->nullable()
            $table->text('email')->nullable();
            $table->text('phone_number01');
            $table->text('phone_number02')->nullable();
            $table->text('message_from_customer')->nullable();

            $table->integer("discount");
            $table->smallInteger("payment_method_id");
            $table->smallInteger("periodic_count");

            $table->boolean("stop_flag");
            //失敗フラグ（旧システムstatus相当、trueが失敗を示す。statusでは何を示すデータかわかりにいくいので列名変更。）
            $table->boolean("failed_flag")->default(false);
            $table->smallInteger("periodic_interval_type_id");
            $table->smallInteger("interval_days")->nullable();
            $table->smallInteger("interval_month")->nullable();
            $table->smallInteger("interval_date_of_month")->nullable();
            $table->date("next_delivery_date");
            $table->date("last_delivery_date")->nullable();

            //2019-02-27 画面表示用に直近に作成した受注の情報 or 定期登録時の選択内容を保持する列を追加。
            //※定期バッチ処理時に更新されるため、管理画面表示の内容がバッチ処理時と同じ値にならない場合もある
            //（例：税率変更前後、など）
            $table->smallInteger("last_delivery_id");
            $table->integer("last_delivery_fee")->nullable();
            $table->integer("last_delivery_fee_tax")->nullable();
            $table->integer("last_payment_method_fee")->nullable();
            $table->integer("last_payment_method_fee_tax")->nullable();
            $table->integer("last_subtotal")->nullable();
            $table->integer("last_subtotal_tax")->nullable();
            $table->integer("last_total")->nullable();
            $table->integer("last_total_tax")->nullable();
            $table->integer("last_payment_total")->nullable();
            $table->integer("last_payment_total_tax")->nullable();

            //購入経路（旧システムのdevice_type_id 相当）
            $table->smallInteger("purchase_route_id")->nullable();

            //注文が確定した日時（クレジットカードの場合決済完了時点、代引の場合は確認画面から遷移する時点）
            $table->timestamp("confirmed_timestamp")->nullable();

            $table->timestamps();
            $table->softDeletes();

            //外部キー
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete("RESTRICT");
            $table->foreign('last_delivery_id')->references('id')->on('deliveries')->onDelete("RESTRICT");
            $table->foreign('payment_method_id')->references('id')->on('payment_methods')->onDelete("RESTRICT");
            $table->foreign('periodic_interval_type_id')->references('id')->on('periodic_interval_types')->onDelete("RESTRICT");

            //インデクス
            $table->index("next_delivery_date");

        });


        if(config("database.default")==="pgsql"){
            //簡易検索画面での検索条件となる、名前、カナ
            DB::statement('CREATE INDEX periodic_orders_name_idx ON periodic_orders ((name01 || name02));' );
            DB::statement('CREATE INDEX periodic_orders_kana_idx ON periodic_orders ((kana01 || kana02));' );

            /**
             * チェック制約：定期間隔の列状態に対する制約。
             * 日数間隔指定の場合、日数列が0より大きいこと
             * ◯ヶ月ごと△日指定の場合、月数、指定日がいずれも0より大きいこと
             */
            DB::statement("ALTER TABLE periodic_orders ADD CHECK ((periodic_interval_type_id = 1 AND interval_days > 0) <> (periodic_interval_type_id = 2 AND interval_month > 0 AND interval_date_of_month > 0));");
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('periodic_orders');
    }
}
