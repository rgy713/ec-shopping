<?php

/**
 * ユーザー画面、管理画面共通
 */
return[
    //項目の名称
    "item_name"=>[
        //住所関連
        "address"=>[
            "name"=>"お名前",
            "kana"=>"フリガナ",
            "tel"=>"電話番号",
            "prefecture"=>"都道府県",
            "zip"=>"郵便番号",
            "address1"=>"市区町村",
            "address2"=>"番地以降",
            "address3"=>"配送要望",
        ],
        //注文関連
        "order"=>[
            "tax"=>"消費税",
            "discount"=>"値引き",
            "delivery_fee"=>"配送料",
            "payment_fee"=>"手数料",
            "subtotal"=>"小計",
            "total"=>"合計",
            "payment_total"=>"お支払い合計",
            "delivery_provider"=>"配送業者",
            "payment_method"=>"支払い方法"
        ],
        //その他
        "remark"=>"備考",
        "birthday"=>"誕生日",
    ],
    //
    "placeholder"=>[
        "address"=>[
            "name01"=>"苗字",
            "name02"=>"名前",
            "kana01"=>"ミョウジ",
            "kana02"=>"ナマエ",
            "address1"=>"市区町村",
            "address2"=>"番地・建物名・部屋番号",
            "address3"=>"配送要望",
        ],

    ],
    //POST status message
    "response"=>[
        "success"=>"成功しました。",
        "error"=>"失敗しました。"
    ]
];