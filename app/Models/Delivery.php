<?php

namespace App\Models;

use App\Models\Interfaces\KeyValueListModelInterface;
use App\Models\Traits\DefaultKeyValueListTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Delivery
 * @package App\Models
 * @author k.yamamoto@balocco.info
 */
class Delivery extends Model implements KeyValueListModelInterface
{
    use DefaultKeyValueListTrait;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * @author k.yamamoto@balocco.info
     */
    public function deliveryTimes()
    {
        return $this->hasMany(DeliveryTime::class);
    }
}
