<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Admin\BaseController;
use App\Services\TaxService;
use App\Services\FlashToastrMessageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\TaxSettings;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;

class TaxController extends BaseController
{
    private $service;
    private $toastr;

    public function __construct()
    {
        $this->service = app(TaxService::class);
        $this->toastr = app(FlashToastrMessageService::class);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $settings = $this->service->getAll();
        $activedId = $this->service->getActivedId();
        return view("admin.pages.system.tax", compact('settings','activedId'));
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function delete(Request $request)
    {
        $params = $request->all();

        $validator = Validator::make($params, [
            'id' => ['required', 'exists:tax_settings,id'],
        ]);

        if ($validator->fails()) {
            $this->toastr->add('error', implode(" ",$validator->messages()->all()));
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $setting = TaxSettings::find($params['id']);
        if ($setting && Carbon::parse($setting->activated_from)->lt(Carbon::now())) {
            $validator->errors()->add('id', __('validation.hint_text.not_delete_rate'));
            $this->toastr->add('error', implode(" ",$validator->messages()->all()));
            return Redirect::back()->withErrors($validator)->withInput();
        }

        try {
            $this->service->delete($params);
            $this->toastr->add('success',__("common.response.success"));
        } catch (\Exception $e) {
            $this->toastr->add('error', $e->getMessage());
        }

        return Redirect::back();
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function create(Request $request)
    {
        $params = $request->all();

        $validator = Validator::make($params, [
            'activated_from_date' => ['required', 'date', 'date_format:Y-m-d', 'after:'.Carbon::now()->format('Y-m-d')],
            'activated_from_time' => ['required', 'string', 'date_format:H:i'],
            'rate' => ['required', 'integer', 'min:1', 'max:100'],
        ]);

        if ($validator->fails()) {
            $this->toastr->add('error', implode(" ",$validator->messages()->all()));
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $setting = TaxSettings::whereDate('activated_from', '=' , $params['activated_from_date'])->get()->count();
        if ($setting > 0) {
            $validator->errors()->add('activated_from_date', __('validation.hint_text.activated_from_unique'));
            $this->toastr->add('error', implode(" ",$validator->messages()->all()));
            return Redirect::back()->withErrors($validator)->withInput();
        }

        try {
            $this->service->create($params);
            $this->toastr->add('success',__("common.response.success"));
        } catch (\Exception $e) {
            $this->toastr->add('error', $e->getMessage());
        }

        return Redirect::back();
    }
}