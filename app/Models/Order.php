<?php

namespace App\Models;

use App\Models\Masters\OrderStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Order extends Model
{
    use SoftDeletes;

    use Notifiable;

    public function routeNotificationForMail()
    {
        return $this->email;
    }

    protected $dates = ['deleted_at'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function details()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function shipping()
    {
        return $this->hasOne(Shipping::class);
    }

    public function periodicOrder()
    {
        return $this->belongsTo(PeriodicOrder::class);
    }

    public function delivery()
    {
        return $this->belongsTo(Delivery::class);
    }

    public function status()
    {
        return $this->hasOne(OrderStatus::class, 'id', 'order_status_id');
    }

    public function shopMemos()
    {
        return $this->hasMany(ShopMemo::class,'order_id','id');
    }
}
