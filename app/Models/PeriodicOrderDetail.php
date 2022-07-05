<?php

namespace App\Models;

use App\Models\Interfaces\KeyValueListModelInterface;
use App\Models\Traits\DefaultKeyValueListTrait;
use Illuminate\Database\Eloquent\Model;

class PeriodicOrderDetail extends Model implements KeyValueListModelInterface
{
    use DefaultKeyValueListTrait;

    public $timestamps = false;

    static public function getColumnNameOfListValue()
    {
        return 'product_id';
    }

    static public function getColumnNameOfListOrderBy()
    {
        return 'product_id';
    }

    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }
}
