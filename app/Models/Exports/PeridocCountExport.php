<?php

namespace App\Models\Exports;

use App\Models\Traits\DefaultModelExport;
use Illuminate\Support\Facades\DB;

class PeridocCountExport
{
    use DefaultModelExport;

    public function __construct($export_type, $term_from, $term_to)
    {
        $query = DB::table('periodic_count_summary_batch_logs')
            ->select(DB::raw("to_char(periodic_count_summary_batch_logs.created_at, 'YYYY-MM-DD') as created_date,
                            sum(periodic_count_summary_batch_logs.active_count) as active_count,
                            sum(case when periodic_count_summary_batch_log_details.item_lineup_id = 1 then periodic_count_summary_batch_logs.active_count else 0 end) as active_count_cgc,
                            sum(case when periodic_count_summary_batch_log_details.item_lineup_id = 3 then periodic_count_summary_batch_logs.active_count else 0 end) as active_count_mms,
                            sum(case when periodic_count_summary_batch_log_details.item_lineup_id = 4 then periodic_count_summary_batch_logs.active_count else 0 end) as active_count_rm,
                            sum(case when periodic_count_summary_batch_log_details.item_lineup_id = 5 then periodic_count_summary_batch_logs.active_count else 0 end) as active_count_tr,
                            sum(periodic_count_summary_batch_logs.stop_count) as stop_count,
                            sum(case when periodic_count_summary_batch_log_details.item_lineup_id = 1 then periodic_count_summary_batch_logs.stop_count else 0 end) as stop_count_cgc,
                            sum(case when periodic_count_summary_batch_log_details.item_lineup_id = 3 then periodic_count_summary_batch_logs.stop_count else 0 end) as stop_count_mms,
                            sum(case when periodic_count_summary_batch_log_details.item_lineup_id = 4 then periodic_count_summary_batch_logs.stop_count else 0 end) as stop_count_rm,
                            sum(case when periodic_count_summary_batch_log_details.item_lineup_id = 5 then periodic_count_summary_batch_logs.stop_count else 0 end) as stop_count_tr"))
            ->join('periodic_count_summary_batch_log_details', 'periodic_count_summary_batch_log_details.periodic_count_summary_batch_log_id', 'periodic_count_summary_batch_logs.id')
            ->groupby('created_date');

        if ($export_type == 0) {
            $query->where(function($query1) {
                $query1->whereDay('periodic_count_summary_batch_logs.created_at', '15')
                    ->orWhereRaw("extract(month from periodic_count_summary_batch_logs.created_at) = extract(month from periodic_count_summary_batch_logs.created_at + interval '1 day') - 1");
            });
        }

        if (!is_null($term_from) && !is_null($term_to)) {
            $query->whereBetween('periodic_count_summary_batch_logs.created_at', [$term_from, $term_to]);
        }

        $this->query = $query;
    }

    public function collection()
    {
        return $this->query->get();
    }

    public function headings(): array
    {
        $headings = ['集計日', '定期全体', 'CGC稼働', 'MMS稼働', 'RM稼働', 'TR稼働', '定期全体停止', 'CGC停止', 'MMS停止', 'RF停止', 'TR停止'];

        return $headings;
    }

    public function filename()
    {
        return [
            'name'=>'定期稼働集計CSV'
        ];
    }
}