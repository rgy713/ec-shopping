<?php


namespace App\Common\KeyValueLists;


class PeriodicOrderStopFlagList extends KeyValueList
{
    public function definition(): array
    {
        return [
            0 => "稼働",
            1 => "停止",
        ];
    }

}