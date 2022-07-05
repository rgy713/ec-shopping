<?php

namespace App\Common\KeyValueLists;

/**
 * 定期停止事由リスト
 * Class PeriodicStopReasonList
 * @package App\Common\KeyValueLists
 * @author k.yamamoto@balocco.info
 */
class PeriodicStopReasonList extends DateList
{
    public function definition(): array
    {
        return [
            "1"=>"たまっている",
            "2"=>"他社製品（使用する・試したい）",
            "3"=>"金銭面",
            "4"=>"変化感じなかった",
            "5"=>"肌に合わなかった",
            "6"=>"その他（言いたくない・クレーム）",
            "7"=>"受け取り辞退",
            "8"=>"長期不在",
            "9"=>"クレカ（使用不可・期限切れ）",
        ];
    }

}