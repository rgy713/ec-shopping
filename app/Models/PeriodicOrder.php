<?php

namespace App\Models;

use App\Services\Periodic\Interval\PeriodicIntervalFactory;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PeriodicOrder
 * @package App\Models
 * @author k.yamamoto@balocco.info
 */
class PeriodicOrder extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $periodicIntervalFactory;
    /**
     * PeriodicIntervalType constructor.
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->periodicIntervalFactory=app(PeriodicIntervalFactory::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function details()
    {
        return $this->hasMany(PeriodicOrderDetail::class);
    }

    public function shipping()
    {
        return $this->hasOne(PeriodicShipping::class);
    }

    public function shopMemos()
    {
        return $this->hasMany(ShopMemo::class,'periodic_order_id','id');
    }

    /**
     * @param Carbon|null $carbon 基準となる日付。省略した場合、実行時点のnext_delivery_dateを基準に計算する。
     * @return Carbon
     * @author k.yamamoto@balocco.info
     */
    public function createNextDeliveryDate(Carbon $carbon = null){
        $implementation=$this->periodicIntervalFactory->create($this->periodic_interval_type_id,$this->toArray());
        if(is_null($carbon)){
            $carbon=Carbon::createFromFormat("Y-m-d",$this->next_delivery_date);
        }
        return $implementation->nextDeliveryDate($carbon);
    }

}
