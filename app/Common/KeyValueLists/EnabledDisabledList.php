<?php

namespace App\Common\KeyValueLists;

class EnabledDisabledList extends KeyValueList
{
    public function definition(): array
    {
        return [
            1 => "有効",
            0 => "無効",
        ];
    }

}