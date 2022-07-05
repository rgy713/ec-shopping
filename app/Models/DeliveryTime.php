<?php

namespace App\Models;

use App\Scopes\RankOrderAscScope;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DeliveryTime
 * @package App\Models
 * @author k.yamamoto@balocco.info
 */
class DeliveryTime extends Model
{
    /**
     * @author k.yamamoto@balocco.info
     */
    protected static function boot()
    {
        parent::boot();
        //rank 列降順
        static::addGlobalScope(new RankOrderAscScope());
    }

}
