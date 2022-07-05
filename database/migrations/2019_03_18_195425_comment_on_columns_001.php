<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CommentOnColumns001 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(config("database.default")==="pgsql") {
            DB::statement("COMMENT ON COLUMN advertising_media.item_lineup_id IS '広告対象ラインナップ:広告対象となる商品ラインナップのID。旧システムでは、文字列商品名を保持していた。新システムではラインナップIdに変更。';");
            DB::statement("COMMENT ON COLUMN advertising_media.asp_from IS 'ASP広告期間（開始）:ASP媒体作成バッチ処理により挿入される。';");
            DB::statement("COMMENT ON COLUMN advertising_media.asp_to IS 'ASP広告期間（終了）:ASP媒体作成バッチ処理により挿入される。asp_from =< (パラメタ)  < asp_to の式で評価される。※旧システムで2019-03-31 23:59:59 のデータを保持しているレコードは、新システム仕様では2019-04-01 00:00:00 に変換する必要があるため注意。';");
            DB::statement("COMMENT ON COLUMN auto_mail_settings.id IS '自動配信メール設定ID:';");
            DB::statement("COMMENT ON COLUMN auto_mail_settings.mail_template_id IS '自動配信メールテンプレートID:';");
            DB::statement("COMMENT ON COLUMN auto_mail_settings.order_method IS '注文方法条件:';");
            DB::statement("COMMENT ON COLUMN auto_mail_settings.enabled IS '有効フラグ:';");
            DB::statement("COMMENT ON COLUMN customer_pair_relationship_types.id IS '顧客ペアの関係種別ID:主キー';");
            DB::statement("COMMENT ON COLUMN customer_pair_relationship_types.name IS '顧客ペアの関係種別名:関係種別名。管理画面、ユーザー画面などで使われる想定なし。システム開発者が識別できる名称を保存する。';");
            DB::statement("COMMENT ON COLUMN customer_pair_relationship_types.rank IS '顧客ペアの関係種別並び順:マスタテーブル用のrank。昇順で評価。';");
            DB::statement("COMMENT ON COLUMN customer_pair_relationships.id IS '顧客ペアID:主キー';");
            DB::statement("COMMENT ON COLUMN customer_pair_relationships.customer_id_a IS '顧客ID-A:顧客IDの組み合わせの片側。CHECK制約により、若い方の顧客IDをAに保存しないとエラーとなる。';");
            DB::statement("COMMENT ON COLUMN customer_pair_relationships.customer_id_b IS '顧客ID-B:顧客IDの組み合わせの片側。CHECK制約により、若い方の顧客IDをAに保存しないとエラーとなる。';");
            DB::statement("COMMENT ON COLUMN customers.last_buy_date IS '最終購入日:キャンセル・返金の受注を除く、直近の購入日時。ordersの更新時にイベント処理により更新される。';");
            DB::statement("COMMENT ON COLUMN customers.buy_times IS '購入回数:キャンセル・返金の受注、及び、サンプル請求を除く、購入回数。orders更新時にイベント処理により更新される。その顧客の orders.order_count_of_customer_without_cancel の最大値と常に一致する。';");
            DB::statement("COMMENT ON COLUMN customers.buy_total IS '購入金額合計:キャンセル・返金の受注を除く、購入金額の合計値。orders更新時にイベント処理により更新される。';");
            DB::statement("COMMENT ON COLUMN customers.buy_volume IS '購入本数:キャンセル・返金の受注を除く、購入本数。orders更新時にイベント処理により更新される。';");
            DB::statement("COMMENT ON COLUMN customers.dm_flag IS 'DMフラグ:trueの場合、ステップDMバッチ処理時にDM送付対象として抽出される。falseの場合、他のDM送付条件が全て該当していてもDM送付対象とならない。';");
            DB::statement("COMMENT ON COLUMN mail_template_types.id IS 'メールテンプレート種別ID:2019-03-19時点では後述の2種類のIDが存在する。1.旧システムで、mtb_mail_template、dtb_mailtemplateテーブルに格納されていた内容を持つメールテンプレートに割り当てるID。2.旧システムでdtb_auto_mailテーブルに格納されていた内容を持つメールテンプレートに割り当てるID。';");
            DB::statement("COMMENT ON COLUMN mail_template_types.name IS 'メールテンプレート種別名:管理画面利用者が把握しやすい名称であれば内容は問わない。';");
            DB::statement("COMMENT ON COLUMN mail_templates.id IS 'メールテンプレートID:';");
            DB::statement("COMMENT ON COLUMN mail_templates.name IS 'メールテンプレート名:テンプレート名。管理画面利用者が把握しやすい名称であれば、内容は問わない。';");
            DB::statement("COMMENT ON COLUMN mail_templates.mail_layout_id IS 'レイアウトID:テンプレートが利用するレイアウト';");
            DB::statement("COMMENT ON COLUMN mail_templates.subject IS '件名:メール件名';");
            DB::statement("COMMENT ON COLUMN mail_templates.body_file_path IS '本文ファイルパス:';");
            DB::statement("COMMENT ON COLUMN mail_templates.mail_template_type_id IS 'メールテンプレート種別:メールテンプレート種別のIDを持つ。2019-03-19時点では後述の2種類。1.システムメール用テンプレート。システムの特定のタイミングで通知として送信されるメール。2.ステップメール用テンプレート。メール自動配信バッチにより利用される。';");
            DB::statement("COMMENT ON COLUMN mail_templates.sending_trigger IS '送信契機:メールが送信されるタイミングを文字列で表現した内容を格納する。暫定的に通知クラス名を格納しているが、管理画面利用者が把握しやすい内容であればなんでも良い。';");
            DB::statement("COMMENT ON COLUMN order_details.tax IS '商品単価に対する消費税額:当該の商品1点に対する消費税額を保持する。';");
            DB::statement("COMMENT ON COLUMN order_details.quantity IS '数量:購入時に指定される商品数量。productを何点購入するか。';");
            DB::statement("COMMENT ON COLUMN order_details.volume IS '本数:productにSKUが何点含まれるかを示す数値。購入時、products.volumeの値をコピーして保存する。';");
            DB::statement("COMMENT ON COLUMN orders.message_from_customer IS '備考:ユーザー画面購入時にエンドユーザーが入力するメッセージ。';");
            DB::statement("COMMENT ON COLUMN orders.subtotal IS '小計:当該受注に紐付いている受注詳細情報全件の、(price ✕ quantity) の合計値。';");
            DB::statement("COMMENT ON COLUMN orders.subtotal_tax IS '小計に対する消費税額:当該受注に紐付いている受注詳細情報全件の、(tax ✕ quantity) の合計値。';");
            DB::statement("COMMENT ON COLUMN orders.discount IS '値引き額:受注に対する値引き額（税抜き）を保持する。※旧システムでは税込み金額だったため、注意。';");
            DB::statement("COMMENT ON COLUMN orders.total IS '合計金額:小計 + 配送手数料 + 支払手数料 - 値引き額 の計算結果を保持する。';");
            DB::statement("COMMENT ON COLUMN orders.total_tax IS '合計金額に対する消費税額:合計金額に対する消費税額を保持する。';");
            DB::statement("COMMENT ON COLUMN orders.payment_total IS '支払い合計金額:小計 + 配送手数料 + 支払手数料 - 値引き額 の計算結果を保持する。※旧システムでポイント制が存在しており、旧システムデータの場合、使用ポイントの減額分がtotalと異なる。現行システムで作成したデータについては、totalと同じ値となる。';");
            DB::statement("COMMENT ON COLUMN orders.payment_total_tax IS '支払い合計金額に対する消費税額:支払合計金額に対する消費税額を保持する。';");
            DB::statement("COMMENT ON COLUMN orders.catalog_price_subtotal IS '定価小計:当該受注に紐付いている受注詳細情報全件の、(catalog_price ✕ quantity) の合計値。';");
            DB::statement("COMMENT ON COLUMN orders.catalog_price_subtotal_tax IS '定価小計に対する消費税額:当該受注に紐付いている受注詳細情報全件の、(catalog_price_tax ✕ quantity) の合計値。';");
            DB::statement("COMMENT ON COLUMN orders.discount_from_catalog_price IS '定価からの値引き額:catalog_price_subtotal - subtotal の差を保存する。';");
            DB::statement("COMMENT ON COLUMN orders.discount_from_catalog_price_tax IS '定価からの値引き額に対する消費税額:catalog_price_subtotal_tax - subtotal_tax の差を保存する。';");
            DB::statement("COMMENT ON COLUMN orders.settlement_status_code IS '決済状況コード:決済状況コード。旧システムでmemo09に格納される値に相当する情報。';");
            DB::statement("COMMENT ON COLUMN orders.paid_timestamp IS '支払い日時:';");
            DB::statement("COMMENT ON COLUMN orders.sales_timestamp IS '売上計上日時:受注がいつの時点の売上に計上されるかを示すタイムスタンプ。特定の受注ステータスに遷移する際に記録される。詳細については受注ステータスについての仕様を参照。';");
            DB::statement("COMMENT ON COLUMN orders.canceled_timestamp IS 'キャンセル日時:受注がキャンセルされた日時を示すタイムスタンプ。特定の受注ステータスに遷移する際に記録される。詳細については受注ステータスについての仕様を参照。';");
            DB::statement("COMMENT ON COLUMN orders.order_count_of_customer IS '購入者の何回目の購入か:受注が確定する時点で購入者の何件目の受注であるかを記録する通し番号。この列はレコードがその後キャンセルに遷移した場合や、顧客統合をおこなう場合など、一切の状況で更新を行わない。（trigger_orders_before_update() で、更新禁止を定義している。）この数値は、会計部門集計での集計時に参照される。更新禁止する理由は、会計集計部門での「新規」「リピート」集計値が時間経過、状態変化により変化してしまうことを避けるため。';");
            DB::statement("COMMENT ON COLUMN orders.order_count_of_customer_without_cancel IS '購入者の何回目の購入か（キャンセル除く）:購入者の「キャンセル、返金」、「サンプル注文（is_sample）」を除いた何件目の受注であるか」を保持する。受注の確定時には、customers.buy_times  +1 の値を保存する。キャンセル、返金が新たに行われた場合は、その購入者に紐づく受注データ全件について数値を計算し保存し直す。';");
            DB::statement("COMMENT ON COLUMN payment_methods.initial_order_status_id IS '初期受注ステータス:この支払い方法で購入する場合の受注データのステータスID。';");
            DB::statement("COMMENT ON COLUMN shippings.requested_delivery_date IS 'お届け希望日:顧客が希望する、お届け希望の日付。購入時に顧客が指定する。旧システムのdtb_shipping.shipping_dateに相当。';");
            DB::statement("COMMENT ON COLUMN shippings.scheduled_shipping_date IS '発送予定日:発送予定日。帳票発行済みに変更する際、変更実施した日の日付が記録される。旧システムdtb_order.deliv_dateに相当。';");
            DB::statement("COMMENT ON COLUMN shippings.estimated_arrival_date IS '到着予定日:到着予定日。帳票発行済みに変更する際、配送先の都道府県リードタイムを参照して計算され、記録される。旧システムdtb_order.arrival_dateに相当。';");
            DB::statement("COMMENT ON COLUMN shippings.shipped_timestamp IS '発送日時:発送を行った日時。ステータスを発送済み　に変更する時点で記録される。旧システムのdtb_order.commit_dateに相当。';");
            DB::statement("COMMENT ON COLUMN stepdm_settings.is_active IS '有効フラグ:trueの場合、ステップDMバッチの処理対象となる。';");
            DB::statement("COMMENT ON COLUMN system_settings.system_admin_mail_address IS 'システム管理者メールアドレス:operation@fleuri.cc';");
            DB::statement("COMMENT ON COLUMN system_settings.operation_admin_mail_address IS '運用管理者メールアドレス:medicalcourt@balocco.info';");
            DB::statement("COMMENT ON COLUMN system_settings.system_sender_mail_address IS 'システムメール送信者アドレス:customer@fleuri.cc';");
            DB::statement("COMMENT ON COLUMN system_settings.system_sender_signature IS 'システムメール送信者署名:フルリサポートデスク';");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
