<?php

namespace App\Models;

use App\Models\Interfaces\KeyValueListModelInterface;
use App\Models\Traits\DefaultKeyValueListTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PaymentMethod
 * @package App\Models
 * @author k.yamamoto@balocco.info
 */
class PaymentMethod extends Model implements KeyValueListModelInterface
{
    use DefaultKeyValueListTrait;
}
