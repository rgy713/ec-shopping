<?php
/**
 * Created by PhpStorm.
 * User: rgy
 * Date: 4/27/2019
 * Time: 6:20 PM
 */

namespace App\Common\KeyValueLists;


class ItemLineupSampleAList extends KeyValueList
{
    public function definition(): array
    {
        return [
            0 => "指定なし",
            1 => "定期稼働",
            2 => "定期停止",
            3 => "定期していない",
        ];
    }

}