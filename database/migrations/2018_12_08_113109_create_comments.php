<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //式インデクス、postgresql文法に依存するため条件分岐
        if(config("database.default")==="pgsql"){
            DB::statement("COMMENT ON TABLE admin_login_logs IS '管理者ログイン履歴:管理者のログイン履歴を格納する。';");
            DB::statement("COMMENT ON TABLE admin_role_route_settings IS '管理画面表示権限設定:各管理者権限に対し、表示を許可、禁止するルーティングの設定情報を格納する。';");
            DB::statement("COMMENT ON TABLE admin_roles IS '管理者権限:管理者の権限情報を持つ。旧システムmtb_authority に対応するテーブル';");
            DB::statement("COMMENT ON TABLE admins IS '管理者:管理者用ログインアカウント情報。旧システムdtb_memberに対応するテーブル';");
            DB::statement("COMMENT ON TABLE advertising_media IS '広告媒体:広告媒体情報を格納する。広告媒体番号は顧客情報に紐付けられ、集計時に利用される。旧システムdtb_advertising_mediaに対応するテーブル';");
//            DB::statement("COMMENT ON TABLE advertising_media_summary_group_advertising_media IS '広告媒体と広告媒体集計グループの関係:';");
            DB::statement("COMMENT ON TABLE advertising_media_summary_groups IS '広告媒体集計グループ:広告媒体集計用のグループ情報を格納する。';");
            DB::statement("COMMENT ON TABLE asp_media IS 'ASP媒体:月次バッチ処理時に広告媒体を自動作成するASPの情報を格納する。';");
            DB::statement("COMMENT ON TABLE auto_mail_item_lineup IS ':';");
            DB::statement("COMMENT ON TABLE auto_mail_settings IS ':';");
            DB::statement("COMMENT ON TABLE customer_statuses IS '顧客ステータス:顧客ステータスマスタ。旧システムmtb_customer_statusに対応するテーブル';");
            DB::statement("COMMENT ON TABLE customers IS '顧客:顧客情報を格納する。旧システム dtb_customer に対応するテーブル。';");
            DB::statement("COMMENT ON TABLE deliveries IS '配送方法:配送方法マスタ。旧システムdtb_delivに対応するテーブル';");
            DB::statement("COMMENT ON TABLE delivery_fees IS '配送費用:配送費用マスタ。旧システムdtb_delivfeeに対応するテーブル';");
            DB::statement("COMMENT ON TABLE delivery_leadtimes IS '配送リードタイム:配送リードタイムマスタ。旧システムではmtb_pref_deliv_dayに格納されていたデータに対応するが、新システムでは配送方法ごとにリードタイムが持てるよう設計を変更している。';");
            DB::statement("COMMENT ON TABLE delivery_times IS '配送時間帯:配送時間帯マスタ。旧システムdtb_delivtimeに対応するテーブル';");
            DB::statement("COMMENT ON TABLE failed_jobs IS 'ジョブ失敗ログ:Laravel。キューに投入したジョブが失敗した場合、失敗したことを格納する';");
            DB::statement("COMMENT ON TABLE holiday_settings IS '休日設定:休日情報を格納する。データとしては旧システムdtb_holidayに対応するテーブルだが、新システムではシンプルに日付を格納する設計に変更している。';");
            DB::statement("COMMENT ON TABLE item_bundle_settings IS '同梱物設定:定期購入者の受注を作成する際、特定の定期回時に同梱する物品等の設定を格納する。旧システムdtb_periodic_noveltyに対応するテーブル。商品テーブルの設計変更に伴い、商品ID列の格納内容を変更している。';");
            DB::statement("COMMENT ON TABLE item_lineups IS '商品ラインナップ:商品ラインナップマスタ。旧システムmtb_sc_item_lineup';");
            DB::statement("COMMENT ON TABLE mail_histories IS 'メール履歴:システムから送信したメールの履歴を保存する。旧システムdtb_mail_history';");
            DB::statement("COMMENT ON TABLE mail_layouts IS 'メールレイアウト:システムから送信するメールのレイアウト（ヘッダ、フッタ等）の情報を保存する';");
            DB::statement("COMMENT ON TABLE mail_template_types IS 'メールテンプレート種別:メールテンプレートの種別マスタ。';");
            DB::statement("COMMENT ON TABLE mail_templates IS 'メールテンプレート:システムから送信するメールのテンプレート情報を保存する。旧システムのdtb_auto_mail（自動配信メール）、mtb_mail_template、mtb_mail_tpl_path、の情報を1テーブルに整理統合。';");
            DB::statement("COMMENT ON TABLE medium_types IS '媒体種別:媒体種別マスタ。広告媒体の分類。';");
            DB::statement("COMMENT ON TABLE migrations IS 'マイグレーション:Laravel。マイグレーション情報';");
            DB::statement("COMMENT ON TABLE notifications IS '通知:Laravel。通知情報';");
            DB::statement("COMMENT ON TABLE order_details IS '受注詳細:旧システムdtb_order_detail';");
            DB::statement("COMMENT ON TABLE order_statuses IS '受注ステータス:受注ステータスマスタ。旧システムmtb_order_status。受注ステータスの遷移については下記資料参照。https://docs.google.com/spreadsheets/d/1VErGiAeQXq41V5giayGLaHa-6P_bp0-ivipsbTB2AQk/edit?usp=drive_web&ouid=113264954564717787716　';");
            DB::statement("COMMENT ON TABLE orders IS '受注:旧システムdtb_order';");
            DB::statement("COMMENT ON TABLE password_resets IS ':';");
            DB::statement("COMMENT ON TABLE payment_methods IS '支払い方法:支払い方法マスタ。旧システム：dtb_payment';");
            DB::statement("COMMENT ON TABLE periodic_interval_types IS '定期間隔種別:定期間隔種別マスタ。';");
            DB::statement("COMMENT ON TABLE periodic_order_details IS '定期受注詳細:旧システムdtb_periodic_order_detail';");
//            DB::statement("COMMENT ON TABLE periodic_order_statuses IS '定期受注ステータス:定期受注ステータスマスタ';");
            DB::statement("COMMENT ON TABLE periodic_orders IS '定期受注:旧システムdtb_periodic_order';");
            DB::statement("COMMENT ON TABLE periodic_shippings IS '定期出荷情報:旧システムdtb_periodic_shipping';");
            DB::statement("COMMENT ON TABLE pfm_statuses IS 'PFMステータス:PFM（ポートフォリオステータスマネジメント）ステータスマスタ。※旧システムで、「ポートフォリオ分析」と呼ばれていたため、この名称となっている。旧システムmtb_identification_type';");
            DB::statement("COMMENT ON TABLE prefectures IS '都道府県:都道府県マスタ。旧システムmtb_pref';");
            DB::statement("COMMENT ON TABLE product_delivery_types IS '商品配送タイプ:';");
            DB::statement("COMMENT ON TABLE products IS '商品:';");
            DB::statement("COMMENT ON TABLE registration_routes IS '会員登録経路:';");
            DB::statement("COMMENT ON TABLE sales_routes IS '販売経路:';");
            DB::statement("COMMENT ON TABLE sales_targets IS '販売対象:';");
            DB::statement("COMMENT ON TABLE shippings IS '出荷情報:';");
            DB::statement("COMMENT ON TABLE stepdm_histories IS 'ステップDM履歴:';");
            DB::statement("COMMENT ON TABLE stepdm_history_details IS 'ステップDM履歴詳細:';");
            DB::statement("COMMENT ON TABLE stepdm_settings IS 'ステップDM設定:';");
            DB::statement("COMMENT ON TABLE stepdm_types IS 'ステップDM種別:';");
            DB::statement("COMMENT ON TABLE stock_keeping_units IS 'SKU情報:';");
            DB::statement("COMMENT ON TABLE system_events IS 'システム・イベント:';");
            DB::statement("COMMENT ON TABLE system_logs IS 'システムログ:';");
            DB::statement("COMMENT ON TABLE tax_settings IS '消費税設定:';");
            DB::statement("COMMENT ON TABLE undelivered_summary_classifications IS '未発送集計区分:未発送集計区分マスタ';");
            DB::statement("COMMENT ON TABLE users IS ':';");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("COMMENT ON TABLE admin_login_logs IS NULL;");
        DB::statement("COMMENT ON TABLE admin_role_route_settings IS NULL;");
        DB::statement("COMMENT ON TABLE admin_roles IS NULL;");
        DB::statement("COMMENT ON TABLE admins IS NULL;");
        DB::statement("COMMENT ON TABLE advertising_media IS NULL;");
//        DB::statement("COMMENT ON TABLE advertising_media_summary_group_advertising_media IS NULL;");
        DB::statement("COMMENT ON TABLE advertising_media_summary_groups IS NULL;");
        DB::statement("COMMENT ON TABLE asp_media IS NULL;");
        DB::statement("COMMENT ON TABLE auto_mail_item_lineup IS NULL;");
        DB::statement("COMMENT ON TABLE auto_mail_settings IS NULL;");
        DB::statement("COMMENT ON TABLE customer_statuses IS NULL;");
        DB::statement("COMMENT ON TABLE customers IS NULL;");
        DB::statement("COMMENT ON TABLE deliveries IS NULL;");
        DB::statement("COMMENT ON TABLE delivery_fees IS NULL;");
        DB::statement("COMMENT ON TABLE delivery_leadtimes IS NULL;");
        DB::statement("COMMENT ON TABLE delivery_times IS NULL;");
        DB::statement("COMMENT ON TABLE failed_jobs IS NULL;");
        DB::statement("COMMENT ON TABLE holiday_settings IS NULL;");
        DB::statement("COMMENT ON TABLE item_bundle_settings IS NULL;");
        DB::statement("COMMENT ON TABLE item_lineups IS NULL;");
        DB::statement("COMMENT ON TABLE mail_histories IS NULL;");
        DB::statement("COMMENT ON TABLE mail_layouts IS NULL;");
        DB::statement("COMMENT ON TABLE mail_template_types IS NULL;");
        DB::statement("COMMENT ON TABLE mail_templates IS NULL;");
        DB::statement("COMMENT ON TABLE medium_types IS NULL;");
        DB::statement("COMMENT ON TABLE migrations IS NULL;");
        DB::statement("COMMENT ON TABLE notifications IS NULL;");
        DB::statement("COMMENT ON TABLE order_details IS NULL;");
        DB::statement("COMMENT ON TABLE order_statuses IS NULL;");
        DB::statement("COMMENT ON TABLE orders IS NULL;");
        DB::statement("COMMENT ON TABLE password_resets IS NULL;");
        DB::statement("COMMENT ON TABLE payment_methods IS NULL;");
        DB::statement("COMMENT ON TABLE periodic_interval_types IS NULL;");
        DB::statement("COMMENT ON TABLE periodic_order_details IS NULL;");
//        DB::statement("COMMENT ON TABLE periodic_order_statuses IS NULL;");
        DB::statement("COMMENT ON TABLE periodic_orders IS NULL;");
        DB::statement("COMMENT ON TABLE periodic_shippings IS NULL;");
        DB::statement("COMMENT ON TABLE pfm_statuses IS NULL;");
        DB::statement("COMMENT ON TABLE prefectures IS NULL;");
        DB::statement("COMMENT ON TABLE product_delivery_types IS NULL;");
        DB::statement("COMMENT ON TABLE products IS NULL;");
        DB::statement("COMMENT ON TABLE registration_routes IS NULL;");
        DB::statement("COMMENT ON TABLE sales_routes IS NULL;");
        DB::statement("COMMENT ON TABLE sales_targets IS NULL;");
        DB::statement("COMMENT ON TABLE shippings IS NULL;");
        DB::statement("COMMENT ON TABLE stepdm_histories IS NULL;");
        DB::statement("COMMENT ON TABLE stepdm_history_details IS NULL;");
        DB::statement("COMMENT ON TABLE stepdm_settings IS NULL;");
        DB::statement("COMMENT ON TABLE stepdm_types IS NULL;");
        DB::statement("COMMENT ON TABLE stock_keeping_units IS NULL;");
        DB::statement("COMMENT ON TABLE system_events IS NULL;");
        DB::statement("COMMENT ON TABLE system_logs IS NULL;");
        DB::statement("COMMENT ON TABLE tax_settings IS NULL;");
        DB::statement("COMMENT ON TABLE undelivered_summary_classifications IS NULL;");
        DB::statement("COMMENT ON TABLE users IS NULL;");

    }
}
