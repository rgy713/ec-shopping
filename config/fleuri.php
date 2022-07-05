<?php

return[
    'system'=>[
        'format'=>[
            'datetime'=>[
                //blade表示用のdatetimeフォーマットデフォルト値：blade拡張の@datetimeで第二引数省略時に使用
                'view_default'=>'Y-m-d H:i'
            ]
        ],
    ],
    'lists'=>[
        'common_email_domains'=>[
            "gmail.com",
            "icloud.com",
            "docomo.ne.jp",
            "ezweb.ne.jp",
            "i.softbank.ne.jp",
            "yahoo.co.jp",
            "hotmail.co.jp"
        ]
    ],
    'directory_path'=>[
        //メール用のbladeファイルパス：viewの設定にも記載が必要
        'mail_blade'=>[
            "templates"=>"views/mail/templates/",
            "layouts"=>"views/mail/layouts/",
        ]

    ],

];