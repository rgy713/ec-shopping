<?php

/**
 * paygent関連設定項目
 * modenv_properties.phpに記載する内容はリポジトリに格納されるため、
 * リポジトリに格納することが適当でないと考えられるID、パスワード等の項目については
 * .env から取得する意図で下記に配置しました。
 * yamamoto@balocco.info
 */
return [
    'merchant_id' => env('PAYGENT_MERCHANT_ID'),
    'connect_id' => env('PAYGENT_CONNECT_ID'),
    'connect_password' => env('PAYGENT_CONNECT_PASSWORD'),

    //以下、現行システムから関連しそうな設定値を拾ってきて転記している。
    //TODO:必要に応じて（1）.envへの転記、（2）environment以下のディレクトリへの格納、（3）KeyValueListクラスに定義などを検討する

    //電文コード
    'telegram_code'=>[
        '020'=>'カード決済オーソリ',
        '021'=>'カード決済オーソリキャンセル',
        '022'=>'カード決済売上',
        '023'=>'カード決済売上キャンセル',
        '025'=>'カード情報設定',
        '026'=>'カード情報削除',
        '027'=>'カード情報照会',
        '028'=>'カード決済補正オーソリ',
        '029'=>'カード決済補正売上',
    ],

    //トークン決済用javascriptファイル
    'token_js_url'=>[
        'live'=>'https://token.paygent.co.jp/js/PaygentToken.js',
        'sandbox'=>'https://sandbox.paygent.co.jp/js/PaygentToken.js'
    ],
    //クレジット支払方法分類
    'credit_payment_category'=>[
        '10' => '一括払い',
        '61' => '分割払い',
        '80' => 'リボ払い',
        '23' => 'ボーナス一括払い'
    ],


];