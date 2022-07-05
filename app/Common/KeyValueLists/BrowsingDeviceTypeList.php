<?php

namespace App\Common\KeyValueLists;

class BrowsingDeviceTypeList extends KeyValueList
{
    public function definition(): array
    {
        //TODO:DBに格納
        return [
            //旧環境のIDをそのまま引き継ぐため、以下の値。
            10 => "PC",
            2 => "スマートフォン"
        ];
    }
}