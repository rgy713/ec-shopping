<?php

namespace App\Common\KeyValueLists;


class SettlementStatusCodeList extends KeyValueList
{
    public function definition(): array
    {
        return [
            "022"=>"売上",
            "023"=>"売上キャンセル",
            "029"=>"売上変更",
        ];
    }
}