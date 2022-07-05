<?php

namespace App\Http\Controllers\Admin\Order;

use App\Services\FlashToastrMessageService;
use App\Services\OrderUtilityService;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\BaseController;
use Illuminate\Support\Facades\Validator;

class UtilityController extends BaseController
{
    private $service;
    private $toastr;

    public function __construct()
    {
        $this->service = app(OrderUtilityService::class);
        $this->toastr = app(FlashToastrMessageService::class);
    }

    public function index()
    {
        $toImport = count($this->service->getToImportReservation());
        $toNew = count($this->service->getToNewReservation());
        $checkNewCount = $this->service->checkNewReservation();
        $toCancel = count($this->service->paymentCancelOrders());
        return view("admin.pages.order.utility",["toImport"=>$toImport, "toNew"=>$toNew, "checkNewCount"=>$checkNewCount, "toCancel"=>$toCancel]);
    }

    public function reservation()
    {
        $toImport = $this->service->getToImportReservation();
        $toNew = $this->service->getToNewReservation();
        return view("admin.pages.order.utility_reservation_confirm", compact("toImport", "toNew"));
    }

    public function changeOrderStatus()
    {
        try {
            $this->service->changeOrderStatus();
            $this->toastr->add('success',__("common.response.success"));
        } catch (\Exception $e) {
            $this->toastr->add('error', $e->getMessage());
        }

        return back();
    }

    public function duplicate()
    {
        $duplicates = $this->service->getDuplicates();
        return view("admin.pages.order.utility_duplicate_confirm", compact("duplicates"));
    }

    public function mergeCustomer(Request $request)
    {
        $params = $request->all();

        $validator = Validator::make($params, [
            'id' => ['required', 'exists:customer_pair_relationships,id'],
            'media_code_id' => ['nullable', 'exists:advertising_media,id'],
            'merge_type' => ['required', 'in:A,B,N'],
        ]);


        if ($validator->fails()) {
            $this->toastr->add('error', implode(" ",$validator->messages()->all()));
            return back()->withErrors($validator)->withInput();
        }

        try {
            $this->service->mergeCustomer($params);
            $this->toastr->add('success',__("common.response.success"));
        } catch (\Exception $e) {
            $this->toastr->add('error', $e->getMessage());
        }

        return back();
    }

    public function cancel()
    {
        $toCancels = $this->service->paymentCancelOrders();
        return view("admin.pages.order.utility_cancel_confirm", compact("toCancels"));
    }

    public function applyCancel()
    {
        try {
            $this->service->applyCancel();
            $this->toastr->add('success',__("common.response.success"));
        } catch (\Exception $e) {
            $this->toastr->add('error', $e->getMessage());
        }

        return back();
    }
}
