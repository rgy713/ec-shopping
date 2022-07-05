<?php

namespace App\Models\Masters;

use App\Models\Interfaces\KeyValueListModelInterface;
use App\Models\Traits\DefaultKeyValueListTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * Class OrderStatus
 * @package App\Models\Masters
 * @author k.yamamoto@balocco.info
 */
class OrderStatus extends Model implements KeyValueListModelInterface
{

    use DefaultKeyValueListTrait;

    protected $table = 'order_statuses';
}
