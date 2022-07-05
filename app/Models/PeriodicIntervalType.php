<?php

namespace App\Models;

use App\Models\Interfaces\KeyValueListModelInterface;
use App\Models\Traits\DefaultKeyValueListTrait;
use Illuminate\Database\Eloquent\Model;


/**
 * Class PeriodicIntervalType
 * @package App\Models
 */
class PeriodicIntervalType extends Model implements KeyValueListModelInterface
{
    use DefaultKeyValueListTrait;
}