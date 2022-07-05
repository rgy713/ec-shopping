<?php

namespace App\Models;

use App\Common\KeyValueLists\ProductCodeList;
use App\Models\Interfaces\KeyValueListModelInterface;
use App\Models\Traits\DefaultKeyValueListTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Product
 * @package App\Models
 * @author k.yamamoto@balocco.info
 */
class Product extends Model implements KeyValueListModelInterface
{
    use DefaultKeyValueListTrait;

    /**
     * @return string
     * @see DefaultKeyValueListTrait
     * @see ProductCodeList
     * @author k.yamamoto@balocco.info
     */
    static public function getColumnNameOfListValue()
    {
        return 'code';
    }

    /**
     * @return string
     * @see DefaultKeyValueListTrait
     * @see ProductCodeList
     * @author k.yamamoto@balocco.info
     */
    static public function getColumnNameOfListOrderBy()
    {
        return 'code';
    }

    public function skus()
    {
        return $this->hasMany(ProductSku::class, 'product_id', 'id');
    }

    public function purchaseWarnings()
    {
        return $this->hasMany(ProductPurchaseWarning::class, 'product_id', 'id');
    }
}
