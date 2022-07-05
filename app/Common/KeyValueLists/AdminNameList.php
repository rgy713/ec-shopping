<?php
/**
 * Created by PhpStorm.
 * User: rgy
 * Date: 4/24/2019
 * Time: 10:33 AM
 */

namespace App\Common\KeyValueLists;

use App\Models\Admin;

class AdminNameList extends KeyValueList
{
    public function definition(): array
    {
        return app(Admin::class)->getKeyValueList()->toArray();
    }
}