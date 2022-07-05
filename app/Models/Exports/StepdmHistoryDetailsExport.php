<?php
/**
 * Created by PhpStorm.
 * User: rgy
 * Date: 4/17/2019
 * Time: 12:24 AM
 */

namespace App\Models\Exports;

use App\Models\Traits\DefaultModelExport;
use Illuminate\Support\Facades\DB;
use App\Models\Masters\CsvType;
use PDF;

class StepdmHistoryDetailsExport
{

    use DefaultModelExport;

    public function __construct($id)
    {
        $this->csv_type_id = 14;
        $this->query = DB::table('stepdm_history_details')
            ->join('prefectures', 'stepdm_history_details.order_prefecture_id', '=', 'prefectures.id')
            ->where('stepdm_history_details.stepdm_history_id', $id)
            ->orderby('stepdm_history_details.id', 'DESC');
    }

    public function downloadPdf()
    {
        $pdf = PDF::loadView('pdf.stepdm_history', ['data' => $this->collection()]);
//        return view('pdf.stepdm_history', ['data' => $this->collection()]);
        $file_name = CsvType::select('name')->where('id', $this->csv_type_id)->first();

        return $pdf->download($file_name['name'].".pdf");
    }
}