<?php

namespace App\Models;

use App\Models\Masters\StepdmType;
use Illuminate\Database\Eloquent\Model;

class StepdmSetting extends Model
{
    public function stepdmType()
    {
        return $this->hasOne(StepdmType::class, 'id', 'stepdm_type_id');
    }
}
