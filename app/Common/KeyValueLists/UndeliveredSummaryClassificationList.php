<?php


namespace App\Common\KeyValueLists;


class UndeliveredSummaryClassificationList extends KeyValueList
{
    public function definition(): array
    {
        return [
            1 => "通常販売",
            2 => "サンプル販売",
            3 => "無料プレゼント",
        ];
    }

}