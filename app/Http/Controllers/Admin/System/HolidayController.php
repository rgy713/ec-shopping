<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Admin\BaseController;
use App\Services\FlashToastrMessageService;
use App\Services\HolidaySettingService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use App\Common\Api\ApiResponseData;

class HolidayController extends BaseController
{
    private $service;
    private $toastr;

    /**
     * HolidayController constructor.
     */
    public function __construct()
    {
        $this->service = app(HolidaySettingService::class);
        $this->toastr = app(FlashToastrMessageService::class);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $holidays = $this->service->getHolidays();
        return view("admin.pages.system.holiday", compact('holidays'));
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function update(Request $request)
    {
        $params = $request->all();

        $validator = Validator::make($params, [
            'ids' => ['required', 'array'],
            'ids.*'  => ['required', 'distinct', 'exists:holiday_settings,id'],
            'date' => ['required', 'array'],
            'date.*' => ['required', 'string', 'after_or_equal:' . Carbon::now()->firstOfYear()->format("Y-m-d"), 'distinct', 'date_format:Y-m-d'],
            'name' => ['required', 'array'],
            'name.*' => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            $this->toastr->add('error', implode(" ",$validator->messages()->all()));
            return Redirect::back()->withErrors($validator)->withInput();
        }

        try {
            $this->service->update($params);
            $this->toastr->add('success',__("common.response.success"));
        } catch (\Exception $e) {
            $this->toastr->add('error', $e->getMessage());
        }

        return Redirect::back();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $params = $request->all();
        $validator = Validator::make($params, [
            'date' => ['required', 'string', 'date_format:Y-m-d', 'after_or_equal:' . Carbon::now()->firstOfYear()->format("Y-m-d") , 'unique:holiday_settings,date' ],
            'holiday_name' => ['required', 'string', 'max:255'],
        ]);

        $responseData = new ApiResponseData($request);

        if ($validator->fails()) {
            $responseData->message = implode(" ",$validator->messages()->all());
            $responseData->saved = $validator->messages();
            $responseData->status = "error";
            return response()->json($responseData);
        }

        try {
            $this->service->create($params);
            $responseData->message = __("common.response.success");
            $responseData->status = "success";
        } catch (\Exception $e) {
            $responseData->message = $e->getMessage();
            $responseData->status = "error";
        }

        return response()->json($responseData);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function delete(Request $request)
    {
        $params = $request->all();

        $validator = Validator::make($params, [
            'delete_ids' => ['required', 'array'],
            'delete_ids.*'  => ['required', 'exists:holiday_settings,id'],
        ]);

        if ($validator->fails()) {
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
}