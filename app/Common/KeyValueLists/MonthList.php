<?php

namespace App\Common\KeyValueLists;

use Illuminate\Support\Facades\Cache;

class MonthList extends KeyValueList
{
    public function definition(): array
    {
        //TODO:キャッシュする意味ある？
        return Cache::rememberForever('App.Common.KeyValueLists.MonthList',function () {
            return [
                1 => "01",
                2 => "02",
                3 => "03",
                4 => "04",
                5 => "05",
                6 => "06",
                7 => "07",
                8 => "08",
                9 => "09",
                10 => "10",
                11 => "11",
                12 => "12",
            ];
        });
    }

}