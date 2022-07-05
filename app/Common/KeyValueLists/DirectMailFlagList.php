<?php

namespace App\Common\KeyValueLists;

class DirectMailFlagList extends KeyValueList
{
    public function definition(): array
    {
        return [
            1 => '希望する',
            2 => '希望しない'
        ];

    }

}