<?php

namespace App\Models\Masters;

use Illuminate\Database\Eloquent\Model;

class OrderStateTransition extends Model
{

    /**
     * @param $fromStatusId
     * @return OrderStateTransition[]|\Illuminate\Database\Eloquent\Collection
     * @author k.yamamoto@balocco.info
     */
    public function getPermitted($fromStatusId){
        return $this->where("permission","=",true)->where("status_id_from","=",$fromStatusId)->get();
    }

    public function toStatus()
    {
        return $this->hasOne(OrderStatus::class,'id','status_id_to');
    }
}
