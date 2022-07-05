<?php
/**
 * Created by PhpStorm.
 * User: rgy
 * Date: 4/27/2019
 * Time: 6:20 PM
 */

namespace App\Common\KeyValueLists;


class ItemLineupSample1List extends KeyValueList
{
    public function definition(): array
    {
        return [
            0 => "指定なし",
            1 => "使用",
            2 => "未使用",
        ];
    }

}