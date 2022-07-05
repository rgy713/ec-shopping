<?php

namespace App\Models\Masters;

use App\Models\Interfaces\KeyValueListModelInterface;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\DefaultKeyValueListTrait;

class CsvType extends Model implements KeyValueListModelInterface
{
    use DefaultKeyValueListTrait;

    public function csvOutputSettings()
    {
        return $this->hasMany(CsvOutputSetting::class,'csv_type_id','id');
    }
}
