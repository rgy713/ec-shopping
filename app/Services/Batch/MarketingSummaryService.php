<?php

namespace App\Services\Batch;


use App\Services\SummaryService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MarketingSummaryService
{
    public function run($target_month=null)
    {
        if (!isset($target_month)) {
            $target_month = Carbon::now();
        }
        else {
            $target_month = Carbon::createFromFormat("Ym", $target_month);
        }

        $current_year = $target_month->year;
        $current_month = $target_month->month;

        $term_from = Carbon::createFromDate($current_year, $current_month - 1, 1)->startOfDay();
        $term_to = Carbon::createFromDate($current_year, $current_month, 0)->startOfDay();
        $term_to1 = Carbon::createFromDate($current_year, $current_month, 1)->startOfDay();

        $old_count = DB::table('marketing_summary_batch_logs')->where('period_from', $term_from)->count();
        if ($old_count > 0) {
            return -1;
        }

        $summary = app(SummaryService::class)->marketingSummary(1, 1, $term_from, $term_to1);

        $new_total = isset($summary->new_total) ? $summary->new_total : 0;
        $peat_total = isset($summary->peat_total) ? $summary->peat_total : 0;
        $total_total = isset($summary->total_total) ? $summary->total_total : 0;
        $new_customer_count = isset($summary->new_customer_count) ? $summary->new_customer_count : 0;
        $peat_customer_count = isset($summary->peat_customer_count) ? $summary->peat_customer_count : 0;
        $new_count = isset($summary->new_count) ? $summary->new_count : 0;
        $peat_count = isset($summary->peat_count) ? $summary->peat_count : 0;

        $marketing_log = [];
        $marketing_log['created_at'] = $target_month;
        $marketing_log['period_from'] = $term_from;
        $marketing_log['period_to'] = $term_to;
        $marketing_log['sales_of_first'] = $new_total;
        $marketing_log['sales_of_repeat'] = $peat_total;
        $marketing_log['sales'] = $total_total;
        $marketing_log['customer_count'] = $new_customer_count + $peat_customer_count;
        $marketing_log['order_count'] = $new_count + $peat_count;
        $marketing_log['average_unit_price'] = $marketing_log['order_count'] > 0 ? round($marketing_log['sales'] / $marketing_log['order_count']) : 0;

        DB::table('marketing_summary_batch_logs')->insert($marketing_log);

        return 0;
    }
}