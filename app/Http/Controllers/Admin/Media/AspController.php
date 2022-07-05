<?php

namespace App\Http\Controllers\Admin\Media;

use App\Services\Batch\CreateAspMediaCodeService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\BaseController;
use App\Models\AspMedia;
use App\Services\FlashToastrMessageService;
use App\Services\AspService;
use App\Common\Api\ApiResponseData;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class AspController extends BaseController
{
    private $service;
    private $toastr;

    public function __construct()
    {
        $this->service = app(AspService::class);
        $this->toastr = app(FlashToastrMessageService::class);
    }

    public function index()
    {
        $asp_medias = AspMedia::orderby('id', 'DESC')->get();

        $view_params = [
            'asp_medias'=>$asp_medias,
        ];

        return view("admin.pages.media.asp", $view_params);
    }

    public function createSave(Request $request)
    {
        $params = $request->all();

        $validator = Validator::make($params, [
            'asp_name' => ['required', 'string', 'max:255'],
            'asp_remark1' => ['nullable', 'string', 'max:255'],
            'asp_default_cost' => ['required', 'integer', 'min:0'],
            'asp_default_item_lineup_id' => ['required', 'exists:item_lineups,id'],
            'asp_enabled' => ['required', 'in:0,1'],
        ]);

        if ($validator->fails()) {
            $this->toastr->add('error', implode(" ",$validator->messages()->all()));
            return Redirect::back()->withErrors($validator)->withInput();
        }

        try {
            $this->service->createSave($params);
            $this->toastr->add('success',__("common.response.success"));
        } catch (\Exception $e) {
            $this->toastr->add('error', $e->getMessage());
        }

        return $this->index();
    }

    public function editSave(Request $request)
    {
        $params = $request->all();

        $validator = Validator::make($params, [
            'asp_id.*' => ['required', 'exists:asp_media,id'],
            'asp_remark1.*' => ['nullable', 'string', 'max:255'],
            'asp_default_cost.*' => ['required', 'integer', 'min:0'],
            'asp_default_item_lineup_id.*' => ['required', 'exists:item_lineups,id'],
            'asp_enabled.*' => ['required', 'in:0,1'],
        ]);

        if ($validator->fails()) {
            $this->toastr->add('error', implode(" ",$validator->messages()->all()));
            return Redirect::back()->withErrors($validator)->withInput();
        }

        try {
            $this->service->editSave($params);
            $this->toastr->add('success',__("common.response.success"));
        } catch (\Exception $e) {
            $this->toastr->add('error', $e->getMessage());
        }

        return $this->index();
    }

    public function createBatch(Request $request)
    {
        $params = $request->all();

        $validator = Validator::make($params, [
            'asp_id' => ['required', 'exists:asp_media,id'],
        ]);

        $responseData = new ApiResponseData($request);

        if ($validator->fails()) {
            $responseData->message = implode(" ",$validator->messages()->all());
            $responseData->saved = $validator->messages();
            $responseData->status = "error";
            return response()->json($responseData);
        }

        $now = Carbon::now();
        $now_str = $now->format('Ym');

        $current_year = $now->year;
        $current_month = $now->month;

        $before_str = Carbon::createFromDate($current_year, $current_month - 1, 1)->format('Ym');
        $after_str = Carbon::createFromDate($current_year, $current_month + 1, 1)->format('Ym');

        $asp_id = $params['asp_id'];

        try {
            $service = app(CreateAspMediaCodeService::class);
            $service->run($before_str, $asp_id);
            $service->run($now_str, $asp_id);
            $service->run($after_str, $asp_id);
            $responseData->message = __("common.response.success");
            $responseData->status = "success";
        } catch (\Exception $e) {
            $responseData->message = $e->getMessage();
            $responseData->status = "error";
        }

        return response()->json($responseData);
    }
}
