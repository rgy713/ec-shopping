<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterOrdersAddSettlementColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            //決済関連列を一旦削除
            $table->dropColumn("settlement_status_code");
            $table->dropColumn("settlement_status_name");
        });

        Schema::table('orders', function (Blueprint $table) {
            //決済に関する情報列の追加
            //旧memo02、memo07、memo10は現状では用途不明のため列を用意しない。
            $table->text("settlement_vendor")->nullable();//旧memo01相当。決済ベンダ名を保存
            $table->text("settlement_vendor_sub_code")->nullable();//旧memo01相当。決済ベンダ名を保存
            $table->boolean("settlement_result")->nullable();//処理結果：旧memo03相当
            $table->text("settlement_id")->nullable();//旧memo06相当
            $table->text("settlement_card_id")->nullable();//旧quick_memo相当
            $table->text("settlement_status_code")->nullable();//旧memo08相当
            $table->text("settlement_response_code")->nullable();//旧memo04相当
            $table->text("settlement_response_detail")->nullable();//旧memo05相当
            $table->text("settlement_sub_status_code")->nullable();//旧memo09相当
            $table->text("settlement_sub_response_code")->nullable();//追加
            $table->text("settlement_sub_response_detail")->nullable();//追加

            $table->jsonb('settlement_options')->nullable();//上記以外の決済関連情報は、全てこのjsonb列に突っ込む
        });

        if(config("database.default")==="pgsql") {
            DB::statement("COMMENT ON COLUMN orders.settlement_vendor IS '決済ベンダ:旧システムmemo01相当。決済ベンダ名。payment_methods.settlement_vendorの値を保存。リリース時点では、文字列「paygent」が保存される以外の用途は無い。（今後、決済方法を増やすときのための列）';");
            DB::statement("COMMENT ON COLUMN orders.settlement_vendor_sub_code IS '決済ベンダサブコード:決済ベンダ側の支払い方法を示すコード。payment_methods.settlement_vendor_sub_codeの値を保存。リリース時点では、文字列「1」（ペイジェントクレジットを示す）が保存される以外の用途は無い。（今後、決済方法を増やすときのための列）';");
            DB::statement("COMMENT ON COLUMN orders.settlement_result IS '決済結果:旧システムmemo03相当。OKの場合true、NGの場合false。';");
            DB::statement("COMMENT ON COLUMN orders.settlement_id IS 'ベンダ側決済ID:旧システムmemo06相当。決済ベンダ側で発行される一意な決済ID（ペイジェントクレジット応答電文中のpayment_id）を保存する。';");
            DB::statement("COMMENT ON COLUMN orders.settlement_card_id IS 'ベンダ側カードID:旧システムquick_memo 内の、CardSeq相当。決済ベンダ側で発行されるカード番号を特定するためのIDを保存する。カード情報お預かりサービスのドキュメントを参照のこと。';");
            DB::statement("COMMENT ON COLUMN orders.settlement_status_code IS '決済ステータスコード:旧システムmemo08相当。最初に行った決済電文番号を保存する。020';");
            DB::statement("COMMENT ON COLUMN orders.settlement_response_code IS '決済結果エラーコード:旧システムmemo04相当。エラーが発生した場合に、エラーコードを保存する。';");
            DB::statement("COMMENT ON COLUMN orders.settlement_response_detail IS '決済結果エラー詳細:旧システムmemo05相当。エラーが発生した場合に、エラー詳細を保存する。';");
            DB::statement("COMMENT ON COLUMN orders.settlement_sub_status_code IS 'サブ決済ステータスコード:旧システムmemo09相当。直近に送信した電文の番号を保存する。';");
            DB::statement("COMMENT ON COLUMN orders.settlement_sub_response_code IS 'サブ決済エラーコード:直近に送信した電文がエラーの場合のエラーコード';");
            DB::statement("COMMENT ON COLUMN orders.settlement_sub_response_detail IS 'サブ決済エラー詳細:直近に送信した電文がエラーの場合のエラー詳細';");
            DB::statement("COMMENT ON COLUMN orders.settlement_options IS '決済オプション:その他、決済に必要な情報を保存する汎用列。保存内容はアプリケーション側で制御する。';");
        }


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn("settlement_vendor");
            $table->dropColumn("settlement_vendor_sub_code");
            $table->dropColumn("settlement_result");
            $table->dropColumn("settlement_response_code");
            $table->dropColumn("settlement_response_detail");
            $table->dropColumn("settlement_id");
            $table->dropColumn("settlement_card_id");
            $table->dropColumn("settlement_sub_status_code");
            $table->dropColumn("settlement_sub_response_code");
            $table->dropColumn("settlement_sub_response_detail");
            $table->dropColumn("settlement_options");
        });

    }
}
