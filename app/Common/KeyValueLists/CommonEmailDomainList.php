<?php

namespace App\Common\KeyValueLists;

/**
 * 入力補助用のEmailドメイン一覧
 * Class CommonEmailDomainList
 * @package App\Common\KeyValueLists
 * @author k.yamamoto@balocco.info
 */
class CommonEmailDomainList extends KeyValueList
{
    public function definition(): array
    {
        //TODO:DBに格納
        return [
            1 => "gmail.com",
            2 => "icloud.com",
            3 => "docomo.ne.jp",
            4 => "ezweb.ne.jp",
            5 => "i.softbank.ne.jp",
            6 => "yahoo.co.jp",
            7 => "hotmail.co.jp"
        ];
    }

}