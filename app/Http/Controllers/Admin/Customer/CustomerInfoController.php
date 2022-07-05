<?php

namespace App\Http\Controllers\Admin\Customer;

use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\BaseController;
use App\Services\FlashToastrMessageService;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use App\Services\CustomerInfoService;
use App\Common\Api\ApiResponseData;

class CustomerInfoController extends BaseController
{
    private $service;
    private $toastr;

    /**
     * AccountController constructor.
     */
    public function __construct()
    {
        $this->service = app(CustomerInfoService::class);
        $this->toastr = app(FlashToastrMessageService::class);
    }


    public function index($id)
    {
        $params = ["id"=> $id];
        $validator = Validator::make($params, [
            'id' => ['required', 'exists:customers,id'],
        ]);

        if ($validator->fails()) {
            $this->toastr->add('error', implode(" ",$validator->messages()->all()));
            return back();
        }

        $customer = $this->service->getCustomer($id);
        return view("admin.pages.customer.info", compact('customer'));
    }

    public function back()
    {
        $route_name = Cache::get('return_route_name');

        if(isset($route_name))
            return redirect()->route($route_name, ['back'=>true]);
        else
            return back();
    }
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function periodicOrderIntervalUpdate(Request $request)
    {
        $params = $request->all();
        $params['shop_memo_note'] = isset($params['shop_memo_auto']) ? (isset($params['shop_memo_body']) ? $params['shop_memo_auto']."\n".$params['shop_memo_body'] : $params['shop_memo_auto'])  : null;
        $messages = [
            'updated_at.exists' => __('validation.hint_text.aleady_modified'),
            'periodic_order_id.exists' => __('validation.hint_text.not_exist_deleted'),
        ];

        $validator = Validator::make($params, [
            'periodic_order_id' => ['required', 'exists:periodic_orders,id,deleted_at,NULL'],
            'periodic_interval_type_id' => ['required',  'exists:periodic_interval_types,id'],
            'updated_at' => ['required', 'exists:periodic_orders,updated_at,id,'.$params['periodic_order_id']],
            'interval_days'=>['nullable', 'required_if:periodic_interval_type_id,==,1', 'integer', 'min:10', 'max:120'],
            'interval_month'=>['nullable', 'required_if:periodic_interval_type_id,==,2', 'integer', 'min:1', 'max:6'],
            'interval_date_of_month'=>['nullable', 'required_if:periodic_interval_type_id,==,2', 'integer', 'min:1', 'max:28'],
            'shop_memo_note'=>['required', 'string'],
        ],$messages);

        $responseData = new ApiResponseData($request);

        if ($validator->fails()) {
            $responseData->message = implode(" ",$validator->messages()->all());
            $responseData->saved = $validator->messages();
            $responseData->status = "error";
            return response()->json($responseData);
        }

        try {
            $this->service->periodicOrderIntervalUpdate($params);
            $responseData->message = __("common.response.success");
            $responseData->status = "success";
        } catch (\Exception $e) {
            $responseData->message = $e->getMessage();
            $responseData->status = "error";
        }

        return response()->json($responseData);
    }

    public function periodicOrderNextUpdate(Request $request)
    {
        $params = $request->all();
        $params['shop_memo_note'] = isset($params['shop_memo_auto']) ? (isset($params['shop_memo_body']) ? $params['shop_memo_auto']."\n".$params['shop_memo_body'] : $params['shop_memo_auto'])  : null;
        $messages = [
            'updated_at.exists' => __('validation.hint_text.aleady_modified'),
            'periodic_order_id' => __('validation.hint_text.not_exist_deleted'),
        ];

        $validator = Validator::make($params, [
            'periodic_order_id' => ['required', 'exists:periodic_orders,id,deleted_at,NULL'],
            'updated_at' => ['required',  'exists:periodic_orders,updated_at,id,'.$params['periodic_order_id']],
            'next_delivery_date'=>['required', 'string', 'date_format:Y-m-d', 'after:'.date('Y-m-d')],
            'shop_memo_note'=>['required', 'string'],
        ],$messages);

        $responseData = new ApiResponseData($request);

        if ($validator->fails()) {
            $responseData->message = implode(" ",$validator->messages()->all());
            $responseData->saved = $validator->messages();
            $responseData->status = "error";
            return response()->json($responseData);
        }

        try {
            $this->service->periodicOrderNextUpdate($params);
            $responseData->message = __("common.response.success");
            $responseData->status = "success";
        } catch (\Exception $e) {
            $responseData->message = $e->getMessage();
            $responseData->status = "error";
        }

        return response()->json($responseData);
    }

    public function periodicOrderFailUpdate(Request $request)
    {
        $params = $request->all();
        $params['shop_memo_note'] = isset($params['shop_memo_auto']) ? (isset($params['shop_memo_body']) ? $params['shop_memo_auto']."\n".$params['shop_memo_body'] : $params['shop_memo_auto'])  : null;
        $messages = [
            'updated_at.exists' => __('validation.hint_text.aleady_modified'),
            'periodic_order_id' => __('validation.hint_text.not_exist_deleted'),
        ];

        $validator = Validator::make($params, [
            'periodic_order_id' => ['required', 'exists:periodic_orders,id,deleted_at,NULL'],
            'updated_at' => ['required',  'exists:periodic_orders,updated_at,id,'.$params['periodic_order_id']],
            'failed_flag'=>['required', 'integer', 'in:0,1'],
            'shop_memo_note'=>['required', 'string'],
        ],$messages);

        $responseData = new ApiResponseData($request);

        if ($validator->fails()) {
            $responseData->message = implode(" ",$validator->messages()->all());
            $responseData->saved = $validator->messages();
            $responseData->status = "error";
            return response()->json($responseData);
        }

        try {
            $this->service->periodicOrderFailUpdate($params);
            $responseData->message = __("common.response.success");
            $responseData->status = "success";
        } catch (\Exception $e) {
            $responseData->message = $e->getMessage();
            $responseData->status = "error";
        }

        return response()->json($responseData);
    }

    public function periodicOrderStopUpdate(Request $request)
    {
        $params = $request->all();
        $params['shop_memo_note'] = isset($params['shop_memo_auto']) ? (isset($params['shop_memo_body']) ? $params['shop_memo_auto']."\n".$params['shop_memo_body'] : $params['shop_memo_auto'])  : null;
        $messages = [
            'updated_at.exists' => __('validation.hint_text.aleady_modified'),
            'periodic_order_id' => __('validation.hint_text.not_exist_deleted'),
        ];

        $validator = Validator::make($params, [
            'periodic_order_id' => ['required', 'exists:periodic_orders,id,deleted_at,NULL'],
            'updated_at' => ['required',  'exists:periodic_orders,updated_at,id,'.$params['periodic_order_id']],
            'stop_flag'=>['required', 'integer', 'in:0,1'],
            'shop_memo_note'=>['required', 'string'],
        ],$messages);

        $responseData = new ApiResponseData($request);

        if ($validator->fails()) {
            $responseData->message = implode(" ",$validator->messages()->all());
            $responseData->saved = $validator->messages();
            $responseData->status = "error";
            return response()->json($responseData);
        }

        try {
            $this->service->periodicOrderStopUpdate($params);
            $responseData->message = __("common.response.success");
            $responseData->status = "success";
        } catch (\Exception $e) {
            $responseData->message = $e->getMessage();
            $responseData->status = "error";
        }

        return response()->json($responseData);
    }

    public function periodicOrderPaymentUpdate(Request $request)
    {
        $params = $request->all();
        $params['shop_memo_note'] = isset($params['shop_memo_auto']) ? (isset($params['shop_memo_body']) ? $params['shop_memo_auto']."\n".$params['shop_memo_body'] : $params['shop_memo_auto'])  : null;
        $messages = [
            'updated_at.exists' => __('validation.hint_text.aleady_modified'),
            'periodic_order_id' => __('validation.hint_text.not_exist_deleted'),
        ];

        $validator = Validator::make($params, [
            'periodic_order_id' => ['required', 'exists:periodic_orders,id,deleted_at,NULL'],
            'updated_at' => ['required',  'exists:periodic_orders,updated_at,id,'.$params['periodic_order_id']],
            'payment_method_id'=>['required', 'exists:payment_methods,id'],
            'settlement_card_id'=>['required_if:payment_method_id,==,5',],
            'shop_memo_note'=>['required', 'string'],
        ],$messages);

        $responseData = new ApiResponseData($request);

        if ($validator->fails()) {
            $responseData->message = implode(" ",$validator->messages()->all());
            $responseData->saved = $validator->messages();
            $responseData->status = "error";
            return response()->json($responseData);
        }

        try {
            $this->service->periodicOrderPaymentUpdate($params);
            $responseData->message = __("common.response.success");
            $responseData->status = "success";
        } catch (\Exception $e) {
            $responseData->message = $e->getMessage();
            $responseData->status = "error";
        }

        return response()->json($responseData);
    }

    public function getSettlementCards(Request $request)
    {
        $params = $request->all();
        $validator = Validator::make($params, [
            'customer_id' => ['required', 'exists:customers,id,deleted_at,NULL'],
        ]);

        $responseData = new ApiResponseData($request);

        if ($validator->fails()) {
            $responseData->message = implode(" ",$validator->messages()->all());
            $responseData->saved = $validator->messages();
            $responseData->status = "error";
            return response()->json($responseData);
        }

        try {
            $responseData->saved = $this->service->getSettlementCards($params['customer_id']);
            $responseData->message = __("common.response.success");
            $responseData->status = "success";
        } catch (\Exception $e) {
            $responseData->message = $e->getMessage();
            $responseData->status = "error";
        }

        return response()->json($responseData);
    }

    public function attachmentUpload(Request $request)
    {
        $params = $request->all();
        $messages = [
            'file_content.max'=>__('validation.hint_text.file_size_2M'),
            'file_content.mimes'=>__('validation.hint_text.accept_file_type'),
        ];

        $validator = Validator::make($params, [
            'customer_id' => ['required', 'exists:customers,id,deleted_at,NULL'],
            'file_title' => ['required',  'unique:customer_attachments,title,NULL,id,customer_id,'.$params['customer_id']],
            'file_content'=>['required', 'mimes:jpeg,gif,png,pdf', 'max:2048'],
        ], $messages);

        $responseData = new ApiResponseData($request);

        if ($validator->fails()) {
            $responseData->message = implode(" ",$validator->messages()->all());
            $responseData->saved = $validator->messages();
            $responseData->status = "error";
            return response()->json($responseData);
        }

        try {
            $this->service->attachmentUpload($params);
            $responseData->message = __("common.response.success");
            $responseData->status = "success";
        } catch (\Exception $e) {
            $responseData->message = $e->getMessage();
            $responseData->status = "error";
        }

        return response()->json($responseData);
    }

    public function attachmentDelete(Request $request)
    {
        $params = $request->all();
        $messages = [
        ];

        $validator = Validator::make($params, [
            'attachment_id' => ['required',  'exists:customer_attachments,id'],
        ], $messages);


        if ($validator->fails()) {
            $this->toastr->add('error', implode(" ",$validator->messages()->all()));
            return Redirect::back();
        }

        try {
            $this->service->attachmentDelete($params['attachment_id']);
            $this->toastr->add('success',__("common.response.success"));
        } catch (\Exception $e) {
            $this->toastr->add('error', $e->getMessage());
        }
        return Redirect::back();
    }
}
