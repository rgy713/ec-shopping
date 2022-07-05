<?php
/**
 * Created by PhpStorm.
 * User: rgy
 * Date: 5/16/2019
 * Time: 11:33 PM
 */

namespace App\Services;


use App\Models\Order;
use PDF;

class ShippingService
{
    public function deliveryPdf($params)
    {
        $order_ids = $params["export_chk_list"];
        $orders = app(Order::class)->whereIn('id',  array_map('intval',$order_ids))->get();
        $pdf = PDF::loadView('pdf.delivery_report', ['orders' => $orders]);
        return $pdf->download("お買上げ明細書.pdf");
    }
}