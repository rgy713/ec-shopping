<?php
/**
 * admin.common.prefecture
 * admin.customer.id
 * user.customer.name
 * など
 */
return [
//    "help"=>[
//        "common"=>[
//            "for_admin"=>"管理者向け情報。システム動作、出力結果には影響しません。"
//        ],
//        "select"=>[
//            "multiple"=>"Ctrl + クリックで複数選択"
//        ],
//        "periodic"=>[
//            "stop_date1"=>"2014年10月01日以降に定期を停止した顧客が対象",
//            "stop_date2"=>"購入→停止(1)→再稼働→停止(2)という経緯を経ている場合、停止(1)(2)ともに検索にヒットする"
//
//        ],
//        "product"=>[
//            "same_customer"=>"※購入する時点でお客様がログイン or 顧客IDが確定している場合のみ判定が有効",
//            "name_length"=>"最大50文字",
//            "image_pixel"=>"推奨：500px x 500px",
//        ],
//        "tag"=>[
//            "name"=>"管理者が識別するための任意の名称を入力",
//            "rank"=>"他のタグとの位置関係の指定。数値が大きいタグを先に出力。",
//            "vendor"=>"管理者が情報を整理するためのタグのベンダー名称を入力",
//        ],
//        "mail"=>[
//            "system_mail"=>"システムから送信されるメールの送信元メールアドレス",
//            "system_signature"=>"システムから送信されるメールの署名",
//            "system_admin"=>"システム系のメール通知先アドレス。受信可能なメールアドレスを指定すること",
//            "operation_admin"=>"運用系のメール通知先アドレス。受信可能なメールアドレスを指定すること",
//
//        ]
//
//    ],
//    "common"=>[
//        "address"=>[
//            "name"=>"お名前",
//            "kana"=>"フリガナ",
//            "tel"=>"電話番号",
//            "prefecture"=>"都道府県",
//            "zip"=>"郵便番号",
//            "address1"=>"市区町村",
//            "address2"=>"番地・建物名？",
//            "address3"=>"部屋番号？",
//        ],
//        "order"=>[
//            "tax"=>"消費税",
//            "discount"=>"値引き",
//            "delivery_fee"=>"配送料",
//            "payment_fee"=>"手数料",
//            "subtotal"=>"小計",
//            "total"=>"合計",
//            "payment_total"=>"お支払い合計",
//            "delivery_provider"=>"配送業者",
//            "payment_method"=>"支払い方法"
//        ],
//        "remark"=>"備考",
//        "birthday"=>"誕生日",
//        "rank"=>"並び順"
//    ],
//
//    "admin"=>[
//        "common"=>[
//            "prefecture"=>"都道府県",
//            "zip"=>"郵便番号",
//            "address1"=>"市区町村",
//            "address2"=>"番地・建物名？",
//            "address3"=>"部屋番号？",
//            "remark"=>"備考",
//            "birthday"=>"誕生日",
//            "tax"=>"消費税",
//            "discount"=>"値引き",
//            "shop_memo"=>"SHOPメモ",
//            "num_result"=>"表示件数",
//            "creator"=>"作成者",
//            "device_type"=>"デバイス"
//
//        ],
//        "customer"=>[
//            "id"=>"顧客ID",
//            "status"=>"会員状態",
//            "tel_flag"=>"電話可否",
//            "mail_magazine_flag"=>"メルマガ",
//            "dm_flag"=>"DM可否",
//            "created_at"=>"会員登録日",
//            "updated_at"=>"会員情報更新日",
//            "name"=>"顧客名",
//            "kana"=>"フリガナ",
//            "email"=>"Email",
//            "tel"=>"TEL",
//            "birth_month"=>"誕生月",
//            "pfm"=>"PFM属性",
//            "buy_total"=>"購入金額",
//            "last_buy_date"=>"最終購入日",
//            "withdrawal_date"=>"離脱日",
//            "buy_volume"=>"購入本数",
//            "buy_times"=>"購入回数",
//            "tel1"=>"TEL1",
//            "tel2"=>"TEL2",
//        ],
//        "media"=>[
//            "code"=>"広告番号",
//            "name"=>"媒体名",
//            "start_date"=>"開始日",
//            "start_time"=>"開始時刻",
//            "code_group"=>"媒体別",
//            "amount"=>"広告費",
//            "area"=>"エリア",
//            "type"=>"媒体種別",
//            "detail"=>"媒体詳細",
//            "circuration"=>"部数",
//            "call_expected"=>"コール予測数",
//            "offer"=>"オファー",
//            "broadcast_duration"=>"放送枠",
//            "broadcast_minutes"=>"放送時間分数",
//        ],
//        "product"=>[
//            "id"=>"商品ID",
//            "image"=>"商品画像",
//            "price"=>"価格",
//            "catalog_price"=>"定価",
//            "tax"=>"消費税額",
//            "catalog_price_tax"=>"定価税額",
//            "limit_once"=>"購入制限/回",
//            "limit_total"=>"購入制限/計",
//            "lineup"=>"ラインナップ",
//            "target_customer"=>"対象顧客",
//            "sales_route"=>"販売経路",
//            "type"=>"商品種別",
//            "quantity"=>"本数",
//            "name"=>"商品名",
//            "code"=>"商品コード",
//            "remark"=>"備考欄（SHOP専用）",
//            "visible"=>"HP"
//        ],
//        "periodic"=>[
//            "id"=>"定期番号",
//            "stop_flag"=>"稼働状況",
//            "count"=>"定期回数",
//            "next_create_date"=>"次回到着日",
//            "prev_create_date"=>"前回到着日",
//            "interval"=>"定期間隔"
//
//        ],
//        "order"=>[
//            "id"=>"注文番号",
//            "create_date"=>"受注日時",
//            "status"=>"対応状況",
//            "payment_date"=>"入金日",
//            "commit_date"=>"発送日",//旧システム踏襲
//            "deliv_date"=>"発送予定日",//旧システム踏襲
//            "arrival_date"=>"到着予定日",
//            "cancel_date"=>"キャンセル日",
//            "error_flag"=>"エラー表示",//旧システム踏襲、新システムでは削除する可能性あり
//            "how_to_buy"=>"購入方法",
//
//
//        ],
//        "tag"=>[
//            "name"=>"タグ名",
//            "vendor"=>"ベンダー",
//            "enabled"=>"有効/無効",
//            "type"=>"タグ種別",
//            "position"=>"設置位置",
//            "content"=>"タグ内容"
//        ],
//        "mail"=>[
//            "system_admin"=>"システム管理者連絡先",
//            "operation_admin"=>"運用管理者連絡先",
//            "system_mail"=>"システムメールアドレス",
//            "system_signature"=>"システムメール署名",
//        ],
//
//
//    ],
];