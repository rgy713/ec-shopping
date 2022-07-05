<?php
/**
 * Created by PhpStorm.
 * User: rgy
 * Date: 5/19/2019
 * Time: 4:20 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class CustomerPairRelationship extends Model
{
    public function customerA()
    {
        return $this->hasOne(Customer::class, "id","customer_id_a");
    }

    public function customerB()
    {
        return $this->hasOne(Customer::class, "id","customer_id_b");
    }
}