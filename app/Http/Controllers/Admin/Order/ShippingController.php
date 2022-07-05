<?php

namespace App\Http\Controllers\Admin\Order;

use App\Services\FlashToastrMessageService;
use App\Services\ShippingService;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\BaseController;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class ShippingController extends BaseController
{
    private $service;
    private $toastr;

    public function __construct()
    {
        $this->service = app(ShippingService::class);
        $this->toastr = app(FlashToastrMessageService::class);
    }

    public function index()
    {
        $param=[
            "isShipping"=>true,
        ];

        return view("admin.pages.order.shipping",$param);
    }

    public function deliveryPdf(Request $request)
    {
        $params = $request->all();
        $validator = Validator::make($params, [
            'export_chk_list' => ['required', 'array'],
            'export_chk_list.*' => ['required', 'exists:orders,id'],
        ]);

        if ($validator->fails()) {
            $this->toastr->add('error', implode(" ",$validator->messages()->all()));
            return Redirect::back();
        }

        try {
            return $this->service->deliveryPdf($params);
        } catch (\Exception $e) {
            dd($e->getMessage());
            $this->toastr->add('error', $e->getMessage());
        }

        return Redirect::back();
    }
}
