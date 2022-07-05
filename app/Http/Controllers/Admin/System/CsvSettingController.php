<?php

namespace App\Http\Controllers\Admin\System;

use App\Common\Api\ApiResponseData;
use App\Http\Controllers\Admin\BaseController;
use App\Services\CsvSettingService;
use App\Services\FlashToastrMessageService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class CsvSettingController extends BaseController
{
    private $service;
    private $toastr;

    /**
     * AccountController constructor.
     */
    public function __construct()
    {
        $this->service = app(CsvSettingService::class);
        $this->toastr = app(FlashToastrMessageService::class);
    }

    public function index($id)
    {
        $params = ["id"=> $id];
        $validator = Validator::make($params, [
            'id' => ['required', 'exists:csv_types,id'],
        ]);

        if ($validator->fails()) {
            $this->toastr->add('error', implode(" ",$validator->messages()->all()));
            return back();
        }
        $csvType = $this->service->getCsvType($id);
        return view("admin.pages.system.csv_setting", compact('csvType'));
    }

    public function update(Request $request, $id)
    {
        $params = $request->all();
        $params["id"] = $id;
        $validator = Validator::make($params, [
            'id' => ['required', 'exists:csv_types,id'],
            'item'=>['required', 'array'],
            'item.*'=>['required', 'in:true,false'],
            'rank'=>['required', 'array'],
            'rank.*'=>['required', 'integer'],

        ]);

        $responseData = new ApiResponseData($request);

        if ($validator->fails()) {
            $responseData->message = implode(" ",$validator->messages()->all());
            $responseData->saved = $validator->messages();
            $responseData->status = "error";
            return response()->json($responseData);
        }

        try {
            $this->service->update($params);
            $responseData->message = __("common.response.success");
            $responseData->status = "success";
        } catch (\Exception $e) {
            $responseData->message = $e->getMessage();
            $responseData->status = "error";
        }

        return response()->json($responseData);
    }
}