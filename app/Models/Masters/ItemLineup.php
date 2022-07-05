<?php

namespace App\Models\Masters;

use App\Models\Interfaces\KeyValueListModelInterface;
use App\Models\Traits\DefaultKeyValueListTrait;
use Illuminate\Database\Eloquent\Model;


class ItemLineup extends Model implements KeyValueListModelInterface
{
    use DefaultKeyValueListTrait;
}
