<?php

namespace App\Common\KeyValueLists;

class MediaAreaList extends KeyValueList
{
    /**
     * TODO:地域リストの内容が決まっていない QA#170
     * @return array
     * @author k.yamamoto@balocco.info
     */
    public function definition(): array
    {
        $prefecture = app()->make(PrefectureList::class);
        $additional = [
            101 => "全国",
            102 => "首都圏",
            103 => "関東",
            999 => "他に？",
        ];
        return array_merge($prefecture->toArray(), $additional);
    }

}