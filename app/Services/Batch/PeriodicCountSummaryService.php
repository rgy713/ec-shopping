<?php

namespace App\Services\Batch;

use App\Common\KeyValueLists\ItemLineupList;
use App\Services\SummaryService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PeriodicCountSummaryService
{
    public function run($target_date = null)
    {
        if (!isset($target_date)) {
            $target_date = Carbon::today();
        }
        else {
            $target_date = Carbon::createFromFormat("Ymd", $target_date);
        }

        $old_count = DB::table('periodic_count_summary_batch_logs')->whereDate('created_at', $target_date->format('Y-m-d'))->count();

        if ($old_count > 0) {
            return -1;
        }

        $periodic_orders = app(SummaryService::class)->periodicCountSummaryQuery();

        $periodic_orders_summary = $periodic_orders->get()->first();

        $active_count = isset($periodic_orders_summary->active_count) ? $periodic_orders_summary->active_count : 0;
        $stop_count = isset($periodic_orders_summary->stop_count) ? $periodic_orders_summary->stop_count : 0;

        $periodic_count_summary_log = [];
        $periodic_count_summary_log['created_at'] = $target_date;
        $periodic_count_summary_log['active_count'] = $active_count;
        $periodic_count_summary_log['stop_count'] = $stop_count;

        $newId = DB::table('periodic_count_summary_batch_logs')->insertGetId($periodic_count_summary_log);

        $itemLineups = app(ItemLineupList::class);
        $periodic_orders_summary1 = $periodic_orders->addSelect('products.item_lineup_id as item_lineup_id')
            ->groupBy('products.item_lineup_id')
            ->orderBy('products.item_lineup_id')
            ->where('products.item_lineup_id', '<>', 2)
            ->where('products.item_lineup_id', '<>', 6)
            ->get()->keyBy('item_lineup_id')->toArray();

        foreach ($itemLineups as $item_lineup_id => $name) {
            if ($item_lineup_id != 2 && $item_lineup_id != 6) {
                $periodic_count_summary_detail_log = [];
                $periodic_count_summary_detail_log['periodic_count_summary_batch_log_id'] = $newId;
                $periodic_count_summary_detail_log['item_lineup_id'] = $item_lineup_id;

                if (isset($periodic_orders_summary1[$item_lineup_id])) {
                    $summary = $periodic_orders_summary1[$item_lineup_id];
                    $active_count = isset($summary->active_count) ? $summary->active_count : 0;
                    $stop_count = isset($summary->stop_count) ? $summary->stop_count : 0;

                    $periodic_count_summary_detail_log['active_count'] = $active_count;
                    $periodic_count_summary_detail_log['stop_count'] = $stop_count;
                }
                else {
                    $periodic_count_summary_detail_log['active_count'] = 0;
                    $periodic_count_summary_detail_log['stop_count'] = 0;
                }

                DB::table('periodic_count_summary_batch_log_details')->insert($periodic_count_summary_detail_log);
            }
        }

        return 0;
    }
}