<?php

namespace App\Services\Batch;

use App\Models\PeriodicOrder;
use App\Services\OrderService;
use App\Services\PeriodicService;
use App\Environments\Interfaces\Paygent as PaygentInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PeriodicOrderService
{
    public function run(): int
    {
        $exception_count = 0;

        $target_date = Carbon::now();

        $periodic_orders = DB::table('periodic_orders')
            ->select('periodic_orders.id')
            ->whereNull('periodic_orders.deleted_at')
            ->whereNotNull('periodic_orders.confirmed_timestamp')
            ->where('periodic_orders.stop_flag', false)
            ->whereRaw(sprintf("periodic_orders.next_delivery_date < timestamp '%s' + interval '1 day'", $target_date->format('Y-m-d H:i:s')))
            ->orderBy('periodic_orders.id')
            ->get();

        foreach ($periodic_orders as $periodic_order) {
            $periodic_id = $periodic_order->id;
            $periodic = app(PeriodicOrder::class)->find($periodic_id);
            $customer = $periodic->customer;


            $new_order = app(PeriodicService::class)->prepareParametersForAcceptOrder($periodic_order->id, $target_date);

            if (!is_null($new_order)) {
                DB::beginTransaction();
                try {
                    $accepted_order = app(OrderService::class)->acceptOrder(
                        $customer,
                        $new_order['order'],
                        $new_order['orderDetail'],
                        $new_order['shipping'],
                        $target_date
                    );
                } catch (\Exception $e) {
                    DB::rollBack();
                    $exception_count += 1;
                    continue;
                }

                if (!is_null($accepted_order)) {
                    try {
                        app(OrderService::class)->updatePeriodicAfterOrderCreated1($accepted_order, $periodic);
                        app(OrderService::class)->updatePeriodicAfterOrderCreated2($accepted_order, $periodic);
                        app(OrderService::class)->updatePeriodicAfterOrderCreated3($accepted_order, $periodic);
                    } catch (\Exception $e) {
                        DB::rollBack();
                        $exception_count += 1;
                        continue;
                    }

                    DB::commit();

                    try {
                        if ($accepted_order->payment_method_id == 5 && $accepted_order->order_status_id == 22) {
                            $accepted_order->order_status_id = 8;

                            $response = app(PaygentInterface ::class)->execPeriodicOrder($accepted_order, $periodic);

                            if ($response->result == 0) {
                                $accepted_order->order_status_id = 9;
                            } else {
                                $periodic->failed_flag = true;
                            }
                        }

                        $accepted_order->save();
                        $periodic->save();
                    } catch (\Exception $e) {
                        $exception_count += 1;

                        $periodic->failed_flag = true;
                        $periodic->save();

                        continue;
                    }

                    try {
                        app(OrderService::class)->confirmOrder($accepted_order, true);
                    } catch (\Exception $e) {
                        $exception_count += 1;

                        $periodic->failed_flag = true;
                        $periodic->save();

                        continue;
                    }
                } else {
                    DB::rollBack();
                }
            }


        }

        return $exception_count;
    }
}