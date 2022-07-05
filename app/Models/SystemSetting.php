<?php

namespace App\Models;

use App\Exceptions\InvalidDataStateException;
use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    //
    public $incrementing=false;
    public $timestamps=false;

    static public function get(){
        $records=self::all();
        if ($records->count()!==1){
            throw new InvalidDataStateException("system_settings","system_settings table must have only 1 record.");
        }
        return $records->first();
    }
}
