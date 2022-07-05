<?php
/**
 * Created by PhpStorm.
 * User: rgy
 * Date: 5/29/2019
 * Time: 11:06 PM
 */

namespace App\Common\KeyValueLists;


class DayOfWeekList extends KeyValueList
{
    public function definition(): array
    {
        return [
            0=>"日曜日",
            1=>"月曜日",
            2=>"火曜日",
            3=>"水曜日",
            4=>"木曜日",
            5=>"金曜日",
            6=>"土曜日"
        ];
    }
}