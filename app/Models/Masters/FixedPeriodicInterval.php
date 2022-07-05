<?php

namespace App\Models\Masters;

use App\Models\Interfaces\KeyValueListModelInterface;
use App\Models\Traits\DefaultKeyValueListTrait;
use Illuminate\Database\Eloquent\Model;

class FixedPeriodicInterval extends Model implements KeyValueListModelInterface
{
    use DefaultKeyValueListTrait;

    static public function getColumnNameOfListValue(){
        return 'periodic_interval_type_id';
    }

    static public function getColumnNameOfListOrderBy(){
        return 'periodic_interval_type_id';
    }
}
