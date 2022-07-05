<?php
/**
 * Created by PhpStorm.
 * User: rgy
 * Date: 5/23/2019
 * Time: 9:58 PM
 */

namespace App\Services\Batch;


use App\Events\Batch\AutoMailTargetOrderFound;
use App\Models\AutoMailSetting;
use App\Models\MailHistory;
use App\Models\Order;
use App\Notifications\Batch\SendAutoMailNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SendAutoMailService
{
    public function run($target_date = null)
    {
        if (!isset($target_date))
            $target_date = Carbon::today();
        else
            $target_date = Carbon::createFromFormat("Ymd", $target_date);

        $autoMailSettings = app(AutoMailSetting::class)
            ->where("enabled", true)
            ->whereHas('mailTemplates', function ($q){
                $q->where('mail_template_type_id', 2);
            })
            ->get();

        foreach ($autoMailSettings as $autoMailSetting){
            $orders_model = app(Order::class)
                ->whereNotNull("orders.confirmed_timestamp")
                ->whereNotNull("orders.email")
                ->where("orders.order_status_id", 5);

            if($autoMailSetting->order_method == 1)
                $orders_model->whereNull("orders.periodic_order_id")
                    ->where("orders.is_sample", false);
            elseif($autoMailSetting->order_method == 2)
                $orders_model->whereNotNull("orders.periodic_order_id")
                    ->where("orders.is_sample", false);
            elseif($autoMailSetting->order_method == 3)
                $orders_model->where("orders.is_sample", true);

            $orders_model->join("shippings", "orders.id", "shippings.order_id")
                ->where(DB::raw("(extract(epoch from age('{$target_date->toDateString()}', shippings.estimated_arrival_date)) / (86400)::double precision)"), $autoMailSetting->elapsed_days);

            if($autoMailSetting->regular_member_only_flag)
                $orders_model->whereHas("customer", function ($q){
                    $q->where('customers.customer_status_id', 2);
                });

            if($autoMailSetting->first_purchase_only_flag)
                $orders_model->whereHas("customer", function ($q){
                    $q->where('buy_times', 1);
                });

            if($autoMailSetting->customer_mail_magazine_flag)
                $orders_model->whereHas("customer", function ($q){
                    $q->where('mail_magazine_flag', true);
                });

            $item_lineup_ids = array_map('intval', $autoMailSetting->autoMailItemLineup()->pluck("id")->toArray());

            if($autoMailSetting->autoMailItemLineup()->count() >0){
                $orders_model->whereHas("details", function($q) use($item_lineup_ids){
                    $q->whereHas("product", function($q) use($item_lineup_ids){
                        $q->whereIn("item_lineup_id", $item_lineup_ids);
                    });
                });
            }

            $orders = $orders_model->get();

            foreach ($orders as $order){
                $mailHistory_count = app(MailHistory::class)
                    ->where("order_id", $order->id)
                    ->where("auto_mail_settings_id", $autoMailSetting->id)
                    ->count();
                if($mailHistory_count > 0)
                    continue;

                event(new AutoMailTargetOrderFound($order, $autoMailSetting->mail_template_id));
            }
        }

    }
}