<?php
/**
 * Created by PhpStorm.
 * User: rgy
 * Date: 5/26/2019
 * Time: 11:19 PM
 */

namespace App\Services\Batch;


use App\Events\PairOfCustomersMerged;
use App\Models\Customer;
use App\Models\CustomerPairRelationship;
use App\Services\MultipleAccountsService;
use Illuminate\Support\Facades\DB;

class AutoMergeService
{
    public function run()
    {
        $customer_pair_relationship = app(CustomerPairRelationship::class)
            ->where("customer_pair_relationship_type_id", 3)
            ->whereHas("customerA", function($q){
                $q->whereNotNull("confirmed_timestamp")
                    ->whereNull("deleted_at");
            })
            ->whereHas("customerB", function($q){
                $q->whereNotNull("confirmed_timestamp")
                    ->whereNull("deleted_at");
            })
            ->orderBy("customer_id_a","ASC")
            ->orderBy("customer_id_b","ASC")
            ->first();

        if(!isset($customer_pair_relationship))
            return false;

        try {
            app(MultipleAccountsService::class)->mergePairOfCustomers($customer_pair_relationship->customer_id_a, $customer_pair_relationship->customer_id_b, $customer_pair_relationship);
        }
        catch (\Exception $e){
            throw new \Exception("customer_id_a:{$customer_pair_relationship->customer_id_a}, customer_id_b:{$customer_pair_relationship->customer_id_b}, customer_pair_relationship_id:{$customer_pair_relationship->id}");
        }

        return true;
    }
}