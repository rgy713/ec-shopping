<?php

namespace App\Common\KeyValueLists;


class MailTriggerOrderTypeList extends KeyValueList
{
    public function definition(): array
    {
        return [
            1 => "指定しない",
            2 => "通常",
            3 => "定期",
            4 => "サンプル",
        ];
    }

}