<?php

use Illuminate\Database\Seeder;
use App\Models\MailTemplate;

class InitMailTemplatesSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        //システムメール用テンプレート
        $data=[
            ['会員登録','会員登録の完了','101','1','CustomerRegisteredNotification','1'],
            ['住所変更通知','お客様住所変更','201','4','CustomerAddressModifiedNotification','1'],
            ['注文受付','ご注文内容のご確認(自動返信メール)','1','1','OrderConfirmedNotification','1'],
            ['配送手配完了','配送に関するお知らせ','4','1','ShippingScheduledNotification','1'],
            ['発送済み','商品発送のご連絡','6','1','OrderShippedNotification','1'],
            ['発送済み（定期）','商品発送のご連絡(定期宅配便)','12','1','PeriodicOrderShipped','1'],
            ['パスワード再設定','パスワードの再設定（仮）','102','1','PasswordResettingRequestedNotification','1'],
            ['自動配信1到著予定7日後','フルリの佐藤です。ご使用方法は間違っていませんか？','2001','2','SendAutoMailNotification','2'],
            ['自動配信2到著予定7日後','【フルリ】その後はいかがでしょうか','2002','3','SendAutoMailNotification','2'],
            ['自動配信3到著予定日21日後','フルリの佐藤です。効果を実感していらっしゃる方の声をまとめました。','2003','2','SendAutoMailNotification','2'],
            ['自動配信4到著予定日30日後','フルリの佐藤です。クリアゲルクレンズで「なぜ毛穴が目立たなくかるのか」お伝えします。','2004','2','SendAutoMailNotification','2'],
            ['自動配信5到著予定日14日後','フルリの佐藤です。フルリが無添加にこだわる理由です。','2005','2','SendAutoMailNotification','2'],
            ['バッチ処理開始','バッチ処理が開始されました','1001','4','BatchStartedNotification','1'],
            ['バッチ処理失敗','バッチ処理が失敗しました','1002','4','BatchFailedNotification','1'],
            ['ポートフォリオバッチ処理成功','ポートフォリオバッチ処理成功','1003','4','AggregatePortfolioFinishedNotification','1'],
            ['自動統合バッチ処理成功','自動統合バッチ処理成功','1004','4','AutoMergeFinishedNotification','1'],
            ['まとめ配送バッチ処理成功','まとめ配送バッチ処理成功','1005','4','BundleShippingFinishedNotification','1'],
            ['ASP用広告媒体作成バッチ処理成功','ASP用広告媒体作成バッチ処理成功','1006','4','CreateAspMediaCodeFinishedNotification','1'],
            ['定期バッチ処理成功','定期バッチ処理成功','1007','4','PeriodicOrderFinishedNotification','1'],
            ['メール自動配信バッチ処理成功','メール自動配信バッチ処理成功','1008','4','SendAutoMailFinishedNotification','1'],
            ['ステップDMバッチ処理成功','ステップDMバッチ処理成功','1009','4','StepDirectMailFinishedNotification','1'],
            ['管理アカウント無効化バッチ処理成功','管理アカウント無効化バッチ処理成功','1010','4','DisableAdminFinishedNotification','1'],
            ['売上推移バッチ処理成功','売上推移バッチ処理成功','1011','4','MarketingSummaryFinishedNotification','1'],
            ['定期稼働者推移バッチ処理成功','定期稼働者推移バッチ処理成功','1012','4','PeriodicCountSummaryFinishedNotification','1'],
            ['休日祝祭日登録バッチ処理成功','休日祝祭日登録バッチ処理成功','1013','4','RegisterHolidaysFinishedNotification','1'],
            ['定期情報変更通知','定期情報変更','202','4','PeriodicInfoModifiedNotification','1'],
            ['ASP用広告媒体作成バッチ処理警告','ASP用広告媒体作成バッチ処理警告','1014','4','CreateAspMediaCodeWarningNotification','1'],
            ['バッチ処理例外発生件数通知','バッチ処理例外発生件数通知','1015','4','BatchWarningNotification','1'],
        ];

        foreach ($data as $row){
            $model = new MailTemplate();
            $model->name=$row[0];
            $model->subject=$row[1];
            $model->id=$row[2];
            $model->mail_layout_id=$row[3];
            $model->sending_trigger=$row[4];
            $model->mail_template_type_id=$row[5];
            $model->body_file_path="mail.templates.".$row[2];
            $model->save();

        }


    }
}
