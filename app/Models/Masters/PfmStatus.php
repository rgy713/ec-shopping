<?php

namespace App\Models\Masters;

use App\Models\Interfaces\KeyValueListModelInterface;
use App\Models\Traits\DefaultKeyValueListTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PfmStatus
 * @package App\Models\Masters
 * @author k.yamamoto@balocco.info
 */
class PfmStatus extends Model implements KeyValueListModelInterface
{
    use DefaultKeyValueListTrait;
}
