<?php

use Illuminate\Database\Seeder;
use App\Models\MailLayout;
use Illuminate\Support\Facades\Storage;

class InitMailLayoutsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * レイアウト1：お客様用共通レイアウト
         */
        $model = new MailLayout();
        $model->name='お客様送信用共通レイアウト';
        $model->remark="お客様に送信するメールで利用する共通のレイアウトです。";
        $model->header_file_path="mail.layouts.1_header";
        $model->footer_file_path="mail.layouts.1_footer";
        $model->save();

        /**
         * レイアウト2：自動配信メール用レイアウト
         */
        $model = new MailLayout();
        $model->name='フルリ通信用共通レイアウト';
        $model->remark="自動配信メール（フルリ通信）";
        $model->header_file_path="mail.layouts.2_header";
        $model->footer_file_path="mail.layouts.2_footer";
        $model->save();

        /**
         * レイアウト3：自動配信メール用レイアウト2
         */
        $model = new MailLayout();
        $model->name='美容皮膚科品質クレンジング';
        $model->remark="自動配信メール（美容皮膚科品質クレンジング）";
        $model->header_file_path="mail.layouts.3_header";
        $model->footer_file_path="mail.layouts.3_footer";
        $model->save();

        /**
         * レイアウト4：バッチ処理/管理者向けの通知用レイアウト
         */
        $model = new MailLayout();
        $model->name='管理者バッチ通知用';
        $model->remark="バッチ処理実行、エラー、住所変更の発生を管理者向けに通知する場合の共通レイアウト";
        $model->header_file_path="mail.layouts.4_header";
        $model->footer_file_path="mail.layouts.4_footer";
        $model->save();

    }
}
