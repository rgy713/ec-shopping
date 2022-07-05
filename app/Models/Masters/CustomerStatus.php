<?php

namespace App\Models\Masters;

use App\Models\Interfaces\KeyValueListModelInterface;
use App\Models\Traits\DefaultKeyValueListTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CustomerStatus
 * @package App\Models\Masters
 * @author k.yamamoto@balocco.info
 */
class CustomerStatus extends Model implements KeyValueListModelInterface
{
    use DefaultKeyValueListTrait;


}
