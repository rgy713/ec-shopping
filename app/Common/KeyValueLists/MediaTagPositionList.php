<?php

namespace App\Common\KeyValueLists;

class MediaTagPositionList extends KeyValueList
{
    public function definition(): array
    {
        return [
            1 => "&#60;&#47;head&#62; タグの直前",
            2 => "&#60;&#47;body&#62; タグの直前",
        ];
    }

}