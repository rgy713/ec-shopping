<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CommentOnTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(config("database.default")==="pgsql"){
            DB::statement("COMMENT ON TABLE additional_shipping_addresses IS '追加配送先住所:お届け先住所情報';");
            DB::statement("COMMENT ON TABLE admin_login_logs IS '管理者ログイン履歴:管理者のログイン履歴を格納する。';");
            DB::statement("COMMENT ON TABLE admin_role_route_settings IS '管理画面表示権限設定:各管理者権限に対し、表示を許可、禁止するルーティングの設定情報を格納する。';");
            DB::statement("COMMENT ON TABLE admin_roles IS '管理者権限:管理者の権限情報を持つ。旧システムmtb_authority に対応するテーブル';");
            DB::statement("COMMENT ON TABLE admins IS '管理者:管理者用ログインアカウント情報。旧システムdtb_memberに対応するテーブル';");
            DB::statement("COMMENT ON TABLE advertising_media IS '広告媒体:広告媒体情報を格納する。広告媒体番号は顧客情報に紐付けられ、集計時に利用される。旧システムdtb_advertising_mediaに対応するテーブル';");
            DB::statement("COMMENT ON TABLE advertising_media_summary_group_advertising_media IS '広告媒体と広告媒体集計グループの関係:';");
            DB::statement("COMMENT ON TABLE advertising_media_summary_groups IS '広告媒体集計グループ:広告媒体集計用のグループ情報を格納する。';");
            DB::statement("COMMENT ON TABLE asp_media IS 'ASP媒体:ASP用広告番号自動登録バッチ処理時に広告媒体を自動作成するASPの情報を格納する。';");
            DB::statement("COMMENT ON TABLE auto_mail_item_lineup IS 'メール自動配信バッチ商品ラインナップ:';");
            DB::statement("COMMENT ON TABLE auto_mail_settings IS 'メール自動配信バッチ設定:メール自動配信バッチ処理時の設定情報';");
            DB::statement("COMMENT ON TABLE csv_output_settings IS 'CSV出力項目設定:管理画面内で出力可能なCSVファイルの出力項目、出力順を制御するための情報を持つ';");
            DB::statement("COMMENT ON TABLE csv_types IS 'CSV種類マスタ:CSVの種類マスタ';");
            DB::statement("COMMENT ON TABLE customer_attachments IS '顧客添付ファイル:管理画面、顧客情報画面でアップロード可能な、顧客対応の資料ファイル情報を持つ';");
            DB::statement("COMMENT ON TABLE customer_statuses IS '顧客ステータス:顧客ステータスマスタ。旧システムmtb_customer_statusに対応するテーブル';");
            DB::statement("COMMENT ON TABLE customers IS '顧客:顧客情報を格納する。旧システム dtb_customer に対応するテーブル。';");
            DB::statement("COMMENT ON TABLE deliveries IS '配送方法:配送方法マスタ。旧システムdtb_delivに対応するテーブル';");
            DB::statement("COMMENT ON TABLE delivery_fees IS '配送費用:配送費用マスタ。旧システムdtb_delivfeeに対応するテーブル';");
            DB::statement("COMMENT ON TABLE delivery_leadtimes IS '配送リードタイム:配送リードタイムマスタ。旧システムではmtb_pref_deliv_dayに格納されていたデータに対応するが、新システムでは配送方法ごとにリードタイムが持てるよう設計を変更している。';");
            DB::statement("COMMENT ON TABLE delivery_times IS '配送時間帯:配送時間帯マスタ。旧システムdtb_delivtimeに対応するテーブル';");
            DB::statement("COMMENT ON TABLE failed_jobs IS 'ジョブ失敗ログ:Laravel。キューに投入したジョブが失敗した場合、失敗したことを格納する';");
            DB::statement("COMMENT ON TABLE fixed_periodic_intervals IS '商品の固定定期間隔選択肢:商品に設定する固定の定期間隔情報を持つ';");
            DB::statement("COMMENT ON TABLE holiday_settings IS '休日設定:休日情報を格納する。データとしては旧システムdtb_holidayに対応するテーブルだが、新システムではシンプルに日付を格納する設計に変更している。';");
            DB::statement("COMMENT ON TABLE item_bundle_settings IS '同梱物設定:定期購入者の受注を作成する際、特定の定期回時に同梱する物品等の設定を格納する。旧システムdtb_periodic_noveltyに対応するテーブル。商品テーブルの設計変更に伴い、商品ID列の格納内容を変更している。';");
            DB::statement("COMMENT ON TABLE item_lineups IS '商品ラインナップ:商品ラインナップマスタ。旧システムmtb_sc_item_lineup';");
            DB::statement("COMMENT ON TABLE mail_histories IS 'メール履歴:システムから送信したメールの履歴を保存する。旧システムdtb_mail_history';");
            DB::statement("COMMENT ON TABLE mail_layouts IS 'メールレイアウト:システムから送信するメールのレイアウト（ヘッダ、フッタ等）の情報を保存する';");
            DB::statement("COMMENT ON TABLE mail_template_types IS 'メールテンプレート種別:メールテンプレートの種別マスタ。';");
            DB::statement("COMMENT ON TABLE mail_templates IS 'メールテンプレート:システムから送信するメールのテンプレート情報を保存する。旧システムのdtb_auto_mail（自動配信メール）、mtb_mail_template、mtb_mail_tpl_path、の情報を1テーブルに整理統合。';");
            DB::statement("COMMENT ON TABLE marketing_summary_batch_logs IS '売上集計バッチ履歴:売上集計バッチ履歴';");
            DB::statement("COMMENT ON TABLE medium_types IS '媒体種別:媒体種別マスタ。広告媒体の分類。';");
            DB::statement("COMMENT ON TABLE migrations IS 'マイグレーション:Laravel。マイグレーション情報';");
            DB::statement("COMMENT ON TABLE notifications IS '通知:Laravel。通知情報';");
            DB::statement("COMMENT ON TABLE order_bundle_shippings IS 'まとめ配送情報:まとめ配送情報';");
            DB::statement("COMMENT ON TABLE order_details IS '受注詳細:旧システムdtb_order_detail';");
            DB::statement("COMMENT ON TABLE order_state_transitions IS '受注ステータス状態遷移表:受注ステータス状態遷移表、遷移前→遷移後　の組に対して、許可、禁止のフラグ情報を持つ。';");
            DB::statement("COMMENT ON TABLE order_statuses IS '受注ステータス:受注ステータスマスタ。旧システムmtb_order_status。受注ステータスの遷移については下記資料参照。https://docs.google.com/spreadsheets/d/1VErGiAeQXq41V5giayGLaHa-6P_bp0-ivipsbTB2AQk/edit?usp=drive_web&ouid=113264954564717787716　';");
            DB::statement("COMMENT ON TABLE orders IS '受注:旧システムdtb_order';");
            DB::statement("COMMENT ON TABLE password_resets IS 'password_resets:password_resets';");
            DB::statement("COMMENT ON TABLE payment_methods IS '支払い方法:支払い方法マスタ。旧システム：dtb_payment';");
            DB::statement("COMMENT ON TABLE periodic_count_summary_batch_log_details IS '定期稼働者集計バッチ履歴詳細:定期稼働者集計バッチ履歴詳細';");
            DB::statement("COMMENT ON TABLE periodic_count_summary_batch_logs IS '定期稼働者集計バッチ履歴:定期稼働者集計バッチ履歴';");
            DB::statement("COMMENT ON TABLE periodic_interval_types IS '定期間隔種別:定期間隔種別マスタ。';");
            DB::statement("COMMENT ON TABLE periodic_order_details IS '定期受注詳細:旧システムdtb_periodic_order_detail';");
            DB::statement("COMMENT ON TABLE periodic_orders IS '定期受注:旧システムdtb_periodic_order';");
            DB::statement("COMMENT ON TABLE periodic_shippings IS '定期出荷情報:旧システムdtb_periodic_shipping';");
            DB::statement("COMMENT ON TABLE pfm_statuses IS 'PFMステータス:PFM（ポートフォリオステータスマネジメント）ステータスマスタ。※旧システムで、「ポートフォリオ分析」と呼ばれていたため、この名称となっている。旧システムmtb_identification_type';");
            DB::statement("COMMENT ON TABLE prefectures IS '都道府県:都道府県マスタ。旧システムmtb_pref';");
            DB::statement("COMMENT ON TABLE product_delivery_types IS '商品配送タイプ:';");
            DB::statement("COMMENT ON TABLE product_purchase_warnings IS '商品購入時警告設定:商品購入時警告設定';");
            DB::statement("COMMENT ON TABLE products IS '商品:商品情報';");
            DB::statement("COMMENT ON TABLE products_skus IS '商品_SKU:商品とSKUの対応情報を持つ。';");
            DB::statement("COMMENT ON TABLE purchase_routes IS '購入経路マスタ:購入経路マスタ';");
            DB::statement("COMMENT ON TABLE purchase_warning_types IS '購入時警告種別マスタ:購入時警告種別マスタ';");
            DB::statement("COMMENT ON TABLE registration_routes IS '会員登録経路:会員登録経路マスタ';");
            DB::statement("COMMENT ON TABLE sales_routes IS '販売経路:販売経路マスタ';");
            DB::statement("COMMENT ON TABLE sales_targets IS '販売対象:販売対象マスタ';");
            DB::statement("COMMENT ON TABLE shippings IS '出荷情報:受注した注文の配送先情報、お届け希望日時、伝票番号等の情報を持つ';");
            DB::statement("COMMENT ON TABLE shop_memos IS 'Shopメモ:顧客、受注、定期に対するメモ';");
            DB::statement("COMMENT ON TABLE stepdm_histories IS 'ステップDMバッチ履歴:ステップDMバッチの実行1回につき、1件の履歴情報を保持するテーブル。';");
            DB::statement("COMMENT ON TABLE stepdm_history_details IS 'ステップDMバッチ履歴詳細:DM内容（コード）と送付先お客様情報を持つ。CSVファイル出力、ラベルシール出力に利用される';");
            DB::statement("COMMENT ON TABLE stepdm_settings IS 'ステップDM設定:ステップDMの送付対象条件（購入からの経過日数、対象商品など）を持つ。ステップDMバッチの挙動を制御する。';");
            DB::statement("COMMENT ON TABLE stepdm_types IS 'ステップDM種別:ステップDM種別';");
            DB::statement("COMMENT ON TABLE stock_keeping_units IS 'SKU情報:SKU情報';");
            DB::statement("COMMENT ON TABLE system_events IS 'システム・イベント:***未使用***';");
            DB::statement("COMMENT ON TABLE system_logs IS 'システムログ:運営管理者に対する通知情報を保存するテーブル。';");
            DB::statement("COMMENT ON TABLE system_settings IS 'システム設定:管理者メールアドレスなどの情報を持つ。このテーブルは常に１レコード存在する状態を維持すること。';");
            DB::statement("COMMENT ON TABLE tag_devices IS 'タグ表示先デバイスマスタ:タグの表示先のデバイス（PC、SP）';");
            DB::statement("COMMENT ON TABLE tag_positions IS 'タグ設置位置マスタ:タグの設置位置（headタグ、bodyタグ）';");
            DB::statement("COMMENT ON TABLE tax_settings IS '消費税設定:';");
            DB::statement("COMMENT ON TABLE undelivered_summary_classifications IS '未発送集計区分:未発送集計区分マスタ';");
            DB::statement("COMMENT ON TABLE user_pages IS 'ユーザー画面マスタ:タグ設置対象として選択可能なユーザー画面の情報';");
            DB::statement("COMMENT ON TABLE users IS '顧客:顧客';");
            DB::statement("COMMENT ON TABLE web_tags IS 'タグ:ユーザー画面に設置するコンバージョンタグ等の情報を持つ';");
            DB::statement("COMMENT ON TABLE web_tags_user_pages IS 'タグ_ユーザー画面対応関係:どのタグをどの画面に表示するか、の関係を持つ';");

        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(config("database.default")==="pgsql") {

            DB::statement("COMMENT ON TABLE additional_shipping_addresses IS NULL;");
            DB::statement("COMMENT ON TABLE admin_login_logs IS NULL;");
            DB::statement("COMMENT ON TABLE admin_role_route_settings IS NULL;");
            DB::statement("COMMENT ON TABLE admin_roles IS NULL;");
            DB::statement("COMMENT ON TABLE admins IS NULL;");
            DB::statement("COMMENT ON TABLE advertising_media IS NULL;");
            DB::statement("COMMENT ON TABLE advertising_media_summary_group_advertising_media IS NULL;");
            DB::statement("COMMENT ON TABLE advertising_media_summary_groups IS NULL;");
            DB::statement("COMMENT ON TABLE asp_media IS NULL;");
            DB::statement("COMMENT ON TABLE auto_mail_item_lineup IS NULL;");
            DB::statement("COMMENT ON TABLE auto_mail_settings IS NULL;");
            DB::statement("COMMENT ON TABLE csv_output_settings IS NULL;");
            DB::statement("COMMENT ON TABLE csv_types IS NULL;");
            DB::statement("COMMENT ON TABLE customer_attachments IS NULL;");
            DB::statement("COMMENT ON TABLE customer_statuses IS NULL;");
            DB::statement("COMMENT ON TABLE customers IS NULL;");
            DB::statement("COMMENT ON TABLE deliveries IS NULL;");
            DB::statement("COMMENT ON TABLE delivery_fees IS NULL;");
            DB::statement("COMMENT ON TABLE delivery_leadtimes IS NULL;");
            DB::statement("COMMENT ON TABLE delivery_times IS NULL;");
            DB::statement("COMMENT ON TABLE failed_jobs IS NULL;");
            DB::statement("COMMENT ON TABLE fixed_periodic_intervals IS NULL;");
            DB::statement("COMMENT ON TABLE holiday_settings IS NULL;");
            DB::statement("COMMENT ON TABLE item_bundle_settings IS NULL;");
            DB::statement("COMMENT ON TABLE item_lineups IS NULL;");
            DB::statement("COMMENT ON TABLE mail_histories IS NULL;");
            DB::statement("COMMENT ON TABLE mail_layouts IS NULL;");
            DB::statement("COMMENT ON TABLE mail_template_types IS NULL;");
            DB::statement("COMMENT ON TABLE mail_templates IS NULL;");
            DB::statement("COMMENT ON TABLE marketing_summary_batch_logs IS NULL;");
            DB::statement("COMMENT ON TABLE medium_types IS NULL;");
            DB::statement("COMMENT ON TABLE migrations IS NULL;");
            DB::statement("COMMENT ON TABLE notifications IS NULL;");
            DB::statement("COMMENT ON TABLE order_bundle_shippings IS NULL;");
            DB::statement("COMMENT ON TABLE order_details IS NULL;");
            DB::statement("COMMENT ON TABLE order_state_transitions IS NULL;");
            DB::statement("COMMENT ON TABLE order_statuses IS NULL;");
            DB::statement("COMMENT ON TABLE orders IS NULL;");
            DB::statement("COMMENT ON TABLE password_resets IS NULL;");
            DB::statement("COMMENT ON TABLE payment_methods IS NULL;");
            DB::statement("COMMENT ON TABLE periodic_count_summary_batch_log_details IS NULL;");
            DB::statement("COMMENT ON TABLE periodic_count_summary_batch_logs IS NULL;");
            DB::statement("COMMENT ON TABLE periodic_interval_types IS NULL;");
            DB::statement("COMMENT ON TABLE periodic_order_details IS NULL;");
            DB::statement("COMMENT ON TABLE periodic_orders IS NULL;");
            DB::statement("COMMENT ON TABLE periodic_shippings IS NULL;");
            DB::statement("COMMENT ON TABLE pfm_statuses IS NULL;");
            DB::statement("COMMENT ON TABLE prefectures IS NULL;");
            DB::statement("COMMENT ON TABLE product_delivery_types IS NULL;");
            DB::statement("COMMENT ON TABLE product_purchase_warnings IS NULL;");
            DB::statement("COMMENT ON TABLE products IS NULL;");
            DB::statement("COMMENT ON TABLE products_skus IS NULL;");
            DB::statement("COMMENT ON TABLE purchase_routes IS NULL;");
            DB::statement("COMMENT ON TABLE purchase_warning_types IS NULL;");
            DB::statement("COMMENT ON TABLE registration_routes IS NULL;");
            DB::statement("COMMENT ON TABLE sales_routes IS NULL;");
            DB::statement("COMMENT ON TABLE sales_targets IS NULL;");
            DB::statement("COMMENT ON TABLE shippings IS NULL;");
            DB::statement("COMMENT ON TABLE shop_memos IS NULL;");
            DB::statement("COMMENT ON TABLE stepdm_histories IS NULL;");
            DB::statement("COMMENT ON TABLE stepdm_history_details IS NULL;");
            DB::statement("COMMENT ON TABLE stepdm_settings IS NULL;");
            DB::statement("COMMENT ON TABLE stepdm_types IS NULL;");
            DB::statement("COMMENT ON TABLE stock_keeping_units IS NULL;");
            DB::statement("COMMENT ON TABLE system_events IS NULL;");
            DB::statement("COMMENT ON TABLE system_logs IS NULL;");
            DB::statement("COMMENT ON TABLE system_settings IS NULL;");
            DB::statement("COMMENT ON TABLE tag_devices IS NULL;");
            DB::statement("COMMENT ON TABLE tag_positions IS NULL;");
            DB::statement("COMMENT ON TABLE tax_settings IS NULL;");
            DB::statement("COMMENT ON TABLE undelivered_summary_classifications IS NULL;");
            DB::statement("COMMENT ON TABLE user_pages IS NULL;");
            DB::statement("COMMENT ON TABLE users IS NULL;");
            DB::statement("COMMENT ON TABLE web_tags IS NULL;");
            DB::statement("COMMENT ON TABLE web_tags_user_pages IS NULL;");
        }
    }
}
