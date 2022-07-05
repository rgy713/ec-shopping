<?php

namespace App\Models;

use App\Models\Interfaces\KeyValueListModelInterface;
use App\Models\Traits\DefaultKeyValueListTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * Class StockKeepingUnit
 * @package App\Models
 * @author k.yamamoto@balocco.info
 */
class StockKeepingUnit extends Model implements KeyValueListModelInterface
{
    use DefaultKeyValueListTrait;

    /**
     * rank 列が無いため、idでソート
     * @return string
     * @author k.yamamoto@balocco.info
     */
    static public function getColumnNameOfListOrderBy()
    {
        return 'id';
    }


}
