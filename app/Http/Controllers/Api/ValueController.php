<?php

namespace App\Http\Controllers\Api;


use App\Models\DeliveryFee;

class ValueController
{
    public function deliveryFee($delivery_id, $prefecture_id)
    {
        $delivery_fee = DeliveryFee::select('fee')
            ->where([['delivery_id', $delivery_id], ['prefecture_id', $prefecture_id]])->first();

        return response()->json($delivery_fee);
    }
}