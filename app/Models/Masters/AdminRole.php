<?php

namespace App\Models\Masters;

use App\Models\Interfaces\KeyValueListModelInterface;
use App\Models\Traits\DefaultKeyValueListTrait;
use Illuminate\Database\Eloquent\Model;

class AdminRole extends Model implements KeyValueListModelInterface
{
    use DefaultKeyValueListTrait;

    /**
     * AdminRoleテーブルにはrank列が無いので、主キー基準でKeyValueListを並べ替える
     * @return string
     * @see DefaultKeyValueListTrait
     * @author k.yamamoto@balocco.info
     */
    static public function getColumnNameOfListOrderBy()
    {
        return 'id';
    }


}
