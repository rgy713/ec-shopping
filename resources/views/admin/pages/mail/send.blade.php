{{-- メール送信/履歴確認画面 --}}
@extends('admin.layouts.main.contents')
@section('title') メール > 通知/履歴 @endsection

@php
    /*$mails=[
        [
            "id"=>1,
            "created_at"=>"2018-11-01 00:00",
            "subject"=>"メールの件名です",
            "template_name"=>"受注メール",
            "body"=>"メール本文",
        ],

        [
            "id"=>2,
            "created_at"=>"2018-10-15 09:00",
            "subject"=>"メールの件名その２です",
            "template_name"=>"受注メール",
            "body"=>"・*:.☆・*:.☆・*:.☆・*:.☆・*:.☆・*:.☆・*:.☆・*:.☆・*:.☆・*:.☆・*:.☆・*:.☆・*:.☆・

美容皮膚科クリニックと美肌フェイシャル専門店の共同開発により誕生した
美容皮膚科品質コスメティックブランド　　Fleuri フルリ　　http://www.fleuri.cc/

・*:.☆・*:.☆・*:.☆・*:.☆・*:.☆・*:.☆・*:.☆・*:.☆・*:.☆・*:.☆・*:.☆・*:.☆・*:.☆・


** ** 様


こんにちは。フルリお客様サポートデスクの佐藤麻奈美と申します。
数ある製品の中より弊社製品をお選び頂きました事、誠に有難うございます。

ご注文の製品を、本日発送させて頂きました。
配送に関し、不具合などがございましたら、何なりとお知らせください。

******************************************************************************************
お届け予定
下記のお荷物伝票番号とアドレスでお荷物の状態がご確認頂けます。

ご注文内容
[ご注文日時] 2017/01/01
[受付番号] 1185559
[製品名]
トリプルリペア 　9,500円　×　1
[お支払方法]クレジット

[小　計] 9,500 円 (税別）
[消費税] 760 円
[送　料] 0 円
[値引き] 0 円
===============
[お支払合計] 10,260 円


お荷物伝票番号：304669207581
現在の配送状況の確認は、
「クロネコヤマトのお荷物お問い合わせ先」
http://toi.kuronekoyamato.co.jp/cgi-bin/tneko
でお調べいただけます。

なお、配送状況が反映されるまで少し時間がかかる事がございます。
お調べ頂いても、お荷物の情報が出てこない場合は
お手数ですが日を改めて頂きますようお願い申し上げます。

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
■ご注意点
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
●配達時にご不在の場合は、「不在票」が郵便受けに入っております。
「不在票」に記載された営業所にお電話頂ければ再配達されます。
●もし到着予定日に製品が届かない場合は、大変ご面倒をお掛けして
恐縮ではございますが、上記の「配送状況追跡サービス」を
ご利用頂くか、弊社までご連絡くださいますようお願い申し上げます。
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━


使用感を選ぶか。安全性を選ぶか。肌結果を選ぶか。

そんな選択を迫られる基礎化粧品作りにおいて、私どもは最初から
「肌結果と安全性」をとことん追求していく事をテーマとして掲げ、
まずはクレンジングの開発からスタートしました。

開発開始から5年が経ち、ようやくクレンジングと洗顔石鹸の
2つだけ製品が完成したというほど長い年月がかかりました。
(現在は化粧水・美容液も完成しています)

最終的な配合比は、本当に偶然と言えるかもしれません。
ひとえに、途中で妥協しなかったから、
神様がくれた「ご褒美」なのかもしれません。

そんな私どもの自信作「フルリ」をぜひ体感なさってください。

なお、ご使用になられる上で疑問や不安などございましたら、
何なりとお気軽にお尋ねください。

この製品を通じてご縁がございました事に心より感謝申し上げます。
今後ともフルリ製品を末永くご愛顧賜りますようお願い申し上げます。

商品のご感想やその他お気づきの点、ご不明な点がございましたら、
お時間がある時にでもぜひお教えくださいませ。
⇒info@fleuri.cc

それでは、製品のご到着を楽しみにお待ちください。

この度は誠に有難うございます。
今後とも何卒宜しくお願い致します。



・*:.☆・*:.☆・*:.☆・*:.☆・*:.☆・*:.☆・*:.☆・*:.☆・*:.☆・*:.☆・*:.☆・
美容皮膚科クリニックと美肌フェイシャル専門店の共同開発により誕生した
美容皮膚科品質コスメティックブランド　Fleuri フルリ
公式サイト　http://www.fleuri.cc/

販売業者:メディカルコート株式会社
所在地:広島市中区中町2-23 並木宮本ビル3F
電話番号:0120-50-2000
・*:.☆・*:.☆・*:.☆・*:.☆・*:.☆・*:.☆・*:.☆・*:.☆・*:.☆・*:.☆・*:.☆・",

        ],




    ];*/
@endphp

@section('contents')
    <div class="row">
        <div class="col-12">
            @include('admin.components.mail.history')
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            @include('admin.components.mail.send_form')
        </div>
    </div>

@endsection
