<?php


namespace App\Common\KeyValueLists;


class MarketingSummaryClassificationList extends KeyValueList
{
    public function definition(): array
    {
        return [

            1 => "通常",
            2 => "LP",
            3 => "定期",
            4 => "直定期"
        ];
    }

}