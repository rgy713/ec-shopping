<?php

namespace App\Common\KeyValueLists;

use App\Models\Masters\AdminRole;

class AdminRoleList extends KeyValueList
{
    public function definition(): array
    {
        return AdminRole::getKeyValueList()->toArray();
    }

}