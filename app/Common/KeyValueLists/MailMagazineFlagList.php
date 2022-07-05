<?php

namespace App\Common\KeyValueLists;


class MailMagazineFlagList extends KeyValueList
{
    public function definition(): array
    {
        return [
            2 => "希望する",
            3 => "希望しない"
        ];
    }

}