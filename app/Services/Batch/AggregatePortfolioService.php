<?php
/**
 * Created by PhpStorm.
 * User: rgy
 * Date: 5/21/2019
 * Time: 9:17 PM
 */

namespace App\Services\Batch;


use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AggregatePortfolioService
{
    public function run() : int
    {
        $customers = app(Customer::class)
            ->whereNull("deleted_at")
            ->whereNotNull("confirmed_timestamp")
            ->where("wholesale_flag",0)
            ->get();

        if(count($customers) == 0){
            return -1;
        }

        $exception_count = 0;

        foreach ($customers as $customer){
            try{
                $orders_model = $customer->orders()
                    ->whereNull("deleted_at")
                    ->whereNotNull("confirmed_timestamp")
                    ->where("order_status_id",5);

                $orders_count = $orders_model->count();

                if($orders_count > 0)
                    $min_created = $orders_model->min("created_at");
                else
                    $min_created = null;

                if($orders_count > 1)
                    $second_min_created = $orders_model->where("created_at", "<>", $min_created)->min("created_at");
                else
                    $second_min_created = null;

                if($orders_count > 0)
                    $max_created = $orders_model->max("created_at");
                else
                    $max_created = null;

                if($orders_count <= 1)
                    $active_days = 0;
                elseif($min_created == $max_created)
                    $active_days = 0;
                elseif($second_min_created == $max_created){
                    $min = Carbon::parse($min_created);
                    $max = Carbon::parse($max_created);
                    $active_days = $min->diffInDays($max);
                }
                else{
                    $min = Carbon::parse($second_min_created);
                    $max = Carbon::parse($max_created);
                    $active_days = $min->diffInDays($max);
                }

                if($active_days == 0)
                    $loss_days = 0;
                else{
                    $min = Carbon::parse($max_created);
                    $max = Carbon::now();
                    $loss_days = $min->diffInDays($max);
                }

                $sum_payment_total = $orders_model->sum("payment_total");
                $year_sum_payment_total = $orders_model->where("created_at", ">", Carbon::now()->subYear(1))->sum("payment_total");

                if($orders_count == 0)
                    $pfm_status_id = 0;
                elseif($active_days >= 210 && $sum_payment_total >= 1000000 && $loss_days < 180 && $year_sum_payment_total >= 1000000)
                    $pfm_status_id = 1;
                elseif($active_days >= 210 && $sum_payment_total >= 1000000 && $loss_days >= 180 && $year_sum_payment_total >= 1000000)
                    $pfm_status_id = 8;
                elseif($active_days >= 210 && $sum_payment_total >= 1000000 && $loss_days < 180 && $year_sum_payment_total < 1000000)
                    $pfm_status_id = 2;
                elseif($active_days >= 210 && $sum_payment_total >= 1000000 && $loss_days >= 180 && $year_sum_payment_total < 1000000)
                    $pfm_status_id = 9;
                elseif($active_days >= 210 && $sum_payment_total >= 100000 && $loss_days < 180)
                    $pfm_status_id = 3;
                elseif($active_days >= 210 && $sum_payment_total >= 100000 && $loss_days >= 180)
                    $pfm_status_id = 10;
                elseif($active_days >= 90 && $sum_payment_total < 100000 && $loss_days < 180 && $orders_count >= 3)
                    $pfm_status_id = 4;
                elseif($active_days >= 90 && $sum_payment_total < 100000 && $loss_days >= 180 && $orders_count >= 3)
                    $pfm_status_id = 11;
                elseif($active_days >= 90 && $sum_payment_total >= 100000 && $loss_days < 180 && $orders_count >= 3)
                    $pfm_status_id = 5;
                elseif($active_days >= 90 && $sum_payment_total >= 100000 && $loss_days >= 180 && $orders_count >= 3)
                    $pfm_status_id = 12;
                elseif(($active_days < 90 && $orders_count>=3) || ($loss_days < 180 && $orders_count == 2))
                    $pfm_status_id = 6;
                elseif($loss_days >= 180 && $orders_count == 2)
                    $pfm_status_id = 13;
                elseif($active_days == 0 && $loss_days < 180)
                    $pfm_status_id = 7;
                elseif($active_days == 0 && $loss_days >= 180)
                    $pfm_status_id = 14;

                $customer->pfm_status_id = $pfm_status_id;
                $customer->save();
            }
            catch (\Exception $e){
                $exception_count += 1;
            }
        }

        $customers = app(Customer::class)->where("wholesale_flag",1)->get();
        foreach ($customers as $customer){
            try{
                $customer->pfm_status_id = 0;
                $customer->save();
            }
            catch (\Exception $e){
                $exception_count += 1;
            }
        }

        return $exception_count;
    }
}