<?php

namespace App\Http\Controllers\Admin\Stepdm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\StepdmService;
use App\Services\ItemBundleSettingService;
use Illuminate\Support\Facades\Validator;
use App\Services\FlashToastrMessageService;
use Illuminate\Support\Facades\Redirect;

class StepdmController extends Controller
{
    private $service;
    private $toastr;

    /**
     * StepdmController constructor.
     */
    public function __construct()
    {
        $this->service = app(StepdmService::class);
        $this->toastr = app(FlashToastrMessageService::class);
    }


    public function download()
    {
        $histories = $this->service->getHistories();
        return view("admin.pages.stepdm.download", compact('histories'));
    }

    public function downloadCsv(Request $request)
    {
        $params = $request->all();
        $validator = Validator::make($params, [
            'id' => ['required', 'exists:stepdm_histories,id'],
        ]);

        if ($validator->fails()) {
            $this->toastr->add('error', implode(" ",$validator->messages()->all()));
            return Redirect::back();
        }

        $id = $params['id'];

        try {
            return $this->service->downloadCsv($id);
        } catch (\Exception $e) {
            $this->toastr->add('error', $e->getMessage());
        }

        return Redirect::back();
    }

    public function downloadPdf(Request $request)
    {
        $params = $request->all();
        $validator = Validator::make($params, [
            'id' => ['required', 'exists:stepdm_histories,id'],
        ]);

        if ($validator->fails()) {
            $this->toastr->add('error', implode(" ",$validator->messages()->all()));
            return Redirect::back();
        }

        $id = $params['id'];

        try {
            return $this->service->downloadPdf($id);
        } catch (\Exception $e) {
            dd($e->getMessage());
            $this->toastr->add('error', $e->getMessage());
        }

        return Redirect::back();
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function setting()
    {
        $stepdm_settings = $this->service->getSettings();

        $item_service = app(ItemBundleSettingService::class);
        $item_settings = $item_service->getSettings();

        return view("admin.pages.stepdm.setting", compact('stepdm_settings', 'item_settings'));
    }
}
