<?php


namespace App\Common\KeyValueLists;


class CustomerTelFlagList extends KeyValueList
{
    public function definition(): array
    {
        return [
            1 => "可",
            2 => "否"
        ];
    }

}