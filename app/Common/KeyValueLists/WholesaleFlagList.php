<?php

namespace App\Common\KeyValueLists;

use Illuminate\Support\Facades\Cache;

class WholesaleFlagList extends KeyValueList
{
    public function definition(): array
    {
        return [
            0 => "卸",
            1 => "一般",
        ];

    }

}