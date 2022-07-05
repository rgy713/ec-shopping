<?php
/**
 * Created by PhpStorm.
 * User: rgy
 * Date: 4/12/2019
 * Time: 10:56 PM
 */

namespace App\Services;

use App\Models\Masters\CsvOutputSetting;
use App\Models\StepdmSetting;
use App\Models\Product;
use App\Models\StepdmHistory;
use App\Models\Exports\StepdmHistoryDetailsExport;

class StepdmService
{

    protected $csvTypeId=14;

    /**
     * @return mixed
     */
    public function getSettings()
    {
        $settings = StepdmSetting::select('t0.*', 't1.name as product_name')
            ->from(sprintf('%s as t0', StepdmSetting::make()->getTable()))
            ->leftJoin(sprintf('%s as t1', Product::make()->getTable()), 't0.product_id','t1.id')
            ->orderBy('t0.id', 'ASC')
            ->orderBy('t0.req_elapsed_days_from_sending_out', 'ASC')
            ->get();

        return $settings;
    }

    /**
     * @return mixed
     */
    public function getHistories()
    {
        // テーブルから直近10件
        $list = StepdmHistory::select()
            ->orderBy('created_at', 'DESC')
//            ->limit(10)
            ->get();
        return $list;
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function downloadCsv($id)
    {
        $stepdm_history_details_export = new StepdmHistoryDetailsExport($id);
        return $stepdm_history_details_export->download();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function downloadPdf($id)
    {
        $stepdm_history_details_export = new StepdmHistoryDetailsExport($id);
        return $stepdm_history_details_export->downloadPdf();
    }
}