<?php
/**
 * Created by PhpStorm.
 * User: rgy
 * Date: 5/24/2019
 * Time: 10:29 PM
 */

namespace App\Services\Batch;

use App\Models\Order;
use App\Models\StepdmHistory;
use App\Models\StepdmHistoryDetail;
use App\Models\StepdmSetting;
use App\Services\OrderService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StepDirectMailService
{
    public function run($target_date = null): int
    {
        $exception_count = 0;

        if (!isset($target_date))
            $target_date = Carbon::today();
        else
            $target_date = Carbon::createFromFormat("Ymd", $target_date);

        $duplicates = app(StepdmHistory::class)
            ->whereDate('executed_timestamp', $target_date)
            ->get();

        if(count($duplicates) > 0)
            return -1;

        $stepdm_settings = app(StepdmSetting::class)->where("is_active", true)->orderBy("id")->get();

        foreach ($stepdm_settings as $stepdm_setting){
            $order_ids = DB::table("orders")
                ->select("orders.id")
                ->join("order_details", "orders.id", "=", "order_details.order_id")
                ->join("shippings", function($join){
                    $join->on("orders.id", "=", "shippings.order_id")
                        ->whereNotNull("shippings.shipped_timestamp");
                })
                ->join("customers", function($join){
                    $join->on("orders.customer_id", "=", "customers.id")
                        ->where("customers.dm_flag",true)
                        ->whereNull("customers.deleted_at")
                        ->whereNotNull("customers.confirmed_timestamp");
                })
                ->whereNotIn("orders.order_status_id", array_merge(app(OrderService::class)->getCanceledStatuses(), app(OrderService::class)->getRefundedStatuses()))
                ->whereNotNull("orders.confirmed_timestamp")
                ->whereNull("orders.deleted_at")
                ->where("order_details.product_id", $stepdm_setting->product_id)
                ->where(DB::raw("(extract(epoch from age('{$target_date->toDateString()}', shippings.shipped_timestamp::date)) / (86400)::double precision)"), $stepdm_setting->req_elapsed_days_from_sending_out)
                ->groupBy("orders.id")
                ->get();
            try{
                $stepdm_history = new StepdmHistory();
                $stepdm_history->executed_timestamp = Carbon::now();
                $stepdm_history->save();
            }
            catch (\Exception $e){
                $exception_count += 1;
                continue;
            }

            $history_total_count = 0;

            foreach ($order_ids as $order_id){
                $order = app(Order::class)->find($order_id->id);
                $stepdmType_class_name = $stepdm_setting->stepdmType->class_name;
                $params = [
                    'order'     => $order,
                    'stepdm_setting' => $stepdm_setting
                ];
                //※今後、stepdm_type_idが追加される可能性を考慮し、ストラテジーパターンにより実装する。
                //※追加条件を実装するクラス名は、stepdm_types.class_nameに格納。
                try{
                    $stepdmType_class = app( "App\\Services\\Batch\\StepDmStrategy\\" . $stepdmType_class_name , $params);
                    if(!$stepdmType_class->addCondition())
                        continue;
                }
                catch (\Exception $e){
                    $exception_count += 1;
                }
                try {
                    $stepdm_history_detail = new StepdmHistoryDetail();
                    $stepdm_history_detail->stepdm_history_id = $stepdm_history->id;
                    $stepdm_history_detail->stepdm_setting_id = $stepdm_setting->id;
                    $stepdm_history_detail->stepdm_setting_code = $stepdm_setting->code;
                    $stepdm_history_detail->order_id = $order->id;
                    $stepdm_history_detail->order_name01 = $order->name01;
                    $stepdm_history_detail->order_name02 = $order->name02;
                    $stepdm_history_detail->order_kana01 = $order->kana01;
                    $stepdm_history_detail->order_kana02 = $order->kana02;
                    $stepdm_history_detail->order_zipcode = $order->zipcode;
                    $stepdm_history_detail->order_prefecture_id = $order->prefecture_id;
                    $stepdm_history_detail->order_address01 = $order->address01;
                    $stepdm_history_detail->order_address02 = $order->address02;
                    $stepdm_history_detail->order_phone_number01 = $order->phone_number01;
                    $stepdm_history_detail->save();
                    $history_total_count += 1;
                }
                catch(\Exception $e){
                    $exception_count += 1;
                    continue;
                }

            }

            try{
                $stepdm_history->total_count = $history_total_count;
                $stepdm_history->finished_timestamp = Carbon::now();
                $stepdm_history->save();
            }
            catch (\Exception $e){
                $exception_count += 1;
            }
        }

        return $exception_count;
    }
}