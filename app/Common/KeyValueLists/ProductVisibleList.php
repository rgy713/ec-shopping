<?php

namespace App\Common\KeyValueLists;

class ProductVisibleList extends KeyValueList
{
    public function definition(): array
    {
        return [
            1 => "公開",
            0 => "非公開",
        ];
    }

}