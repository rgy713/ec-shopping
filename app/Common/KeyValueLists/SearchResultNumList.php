<?php

namespace App\Common\KeyValueLists;

class SearchResultNumList extends KeyValueList
{
    public function definition(): array
    {
        return [
            100 => "100件",
            500 => "500件",
            1000 => "1000件",
            1500 => "1500件",
        ];
    }


}