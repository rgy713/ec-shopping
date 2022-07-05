<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("customer_id");
            $table->text('name01');
            $table->text('name02');
            $table->text('kana01');
            $table->text('kana02');
            $table->string('zipcode',7);
            $table->smallInteger('prefecture_id');
            $table->text('address01');
            $table->text('address02');
            $table->text('requests_for_delivery')->nullable();
            //顧客テーブルのemail列がNull可となった（管理画面からの登録の場合、メールアドレスなしでの登録可とした）ため、受注データもnull可に変更
            $table->text('email')->nullable();
            $table->text('phone_number01');
            $table->text('phone_number02')->nullable();
            $table->text('message_from_customer')->nullable();

            //受注ステータス
            $table->smallInteger('order_status_id');

            //金額関連
            $table->decimal("tax_rate");
            $table->integer("subtotal");
            $table->integer("subtotal_tax");
            $table->integer("discount");
            $table->integer("total");
            $table->integer("total_tax");
            $table->integer("payment_total");
            $table->integer("payment_total_tax");
            $table->integer("catalog_price_subtotal");
            $table->integer("catalog_price_subtotal_tax");
            $table->integer("discount_from_catalog_price");
            $table->integer("discount_from_catalog_price_tax");

            //配送業者情報
            $table->smallInteger("delivery_id");
            $table->text("delivery_name");
            $table->integer("delivery_fee");
            $table->integer("delivery_fee_tax");

            //支払い方法
            $table->smallInteger("payment_method_id");
            $table->text("payment_method_name");
            $table->integer("payment_method_fee");
            $table->integer("payment_method_fee_tax");

            //決済についての情報を格納する列:旧システムのmemo09を想定。
            $table->text("settlement_status_code")->nullable();
            //決済についての情報（表示文字列）を格納する列
            $table->text("settlement_status_name")->nullable();

            //タイムスタンプ
            //注文が確定した日時（クレジットカードの場合決済完了時点、代引の場合は確認画面から遷移する時点）
            $table->timestamp("confirmed_timestamp")->nullable();

            //支払日時
            $table->timestamp("paid_timestamp")->nullable();
            //売上計上日時
            $table->timestamp("sales_timestamp")->nullable();
            //キャンセル日時
            $table->timestamp("canceled_timestamp")->nullable();

            //定期
            $table->integer("periodic_order_id")->nullable();
            $table->smallInteger("periodic_count")->nullable();

            //集計用情報
            //購入回1（会計部門用、購入時に決定し、変えない）
            $table->smallInteger("order_count_of_customer")->nullable();
            //購入回2（マーケ部門用、キャンセル時にNULLに変更し、回数を繰り上げる）
            $table->smallInteger("order_count_of_customer_without_cancel")->nullable();
            //2018-12-21 追加：旧システムの集計レスポンス悪化の原因を改善したいため、購入時にフラグを立てる仕様に変更
            $table->boolean("is_wholesale")->default(false);

            //購入時警告フラグ（赤エラー）
            $table->boolean("purchase_warning_flag")->default(false);
            //警告表示するかどうかフラグ
            $table->boolean("display_purchase_warning_flag")->default(true);

            //この注文がサンプルに対する注文であるか。
            $table->boolean("is_sample")->default(false);

            //購入経路（旧システムのdevice_type_id 相当）
            $table->smallInteger("purchase_route_id")->nullable();

            $table->timestamps();
            $table->softDeletes();

            //集計で使いそうな列にインデクスを作成
            $table->index("periodic_order_id");
            $table->index("sales_timestamp");
            $table->index("canceled_timestamp");

            //外部キー
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete("RESTRICT");
            $table->foreign('delivery_id')->references('id')->on('deliveries')->onDelete("RESTRICT");
            $table->foreign('payment_method_id')->references('id')->on('payment_methods')->onDelete("RESTRICT");
            $table->foreign('periodic_order_id')->references('id')->on('periodic_orders')->onDelete("RESTRICT");
            $table->foreign('order_status_id')->references('id')->on('order_statuses')->onDelete("RESTRICT");
        });

        //式インデクス、postgresql文法に依存するため条件分岐
        if(config("database.default")==="pgsql"){
            //簡易検索画面での検索条件となる、名前、カナ
            DB::statement('CREATE INDEX orders_name_idx ON orders ((name01 || name02));' );
            DB::statement('CREATE INDEX orders_kana_idx ON orders ((kana01 || kana02));' );
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
