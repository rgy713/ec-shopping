<?php


namespace App\Common\KeyValueLists;


class ProductTypeList extends KeyValueList
{
    public function definition(): array
    {
        return [
            1 => "通常",
            2 => "定期",
            3 => "サンプル",
            4 => "同梱物" //追加された
        ];
    }


}