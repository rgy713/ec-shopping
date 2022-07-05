<?php

namespace App\Http\Controllers\Admin\Customer;

use App\Common\Api\ApiResponseData;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\BaseController;
use App\Services\CustomerService;
use App\Services\FlashToastrMessageService;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class CustomerController extends BaseController
{
    private $service;
    private $toastr;

    /**
     * AccountController constructor.
     */
    public function __construct()
    {
        $this->service = app(CustomerService::class);
        $this->toastr = app(FlashToastrMessageService::class);
    }


    public function create()
    {
        return view("admin.pages.customer.create");
    }

    public function popupCreate()
    {
        return view("admin.pages.customer.popup_create");
    }

    public function createSave(Request $request)
    {
        $params = $request->all();
        //半角英数記号のみ許可
        $messages = [
            'customer_password.regex' => __('validation.hint_text.alpha_num_symbol_both'),
            'customer_email_name.regex'=>__('validation.hint_text.customer_email_domain'),
            'customer_email_domain.regex'=>__('validation.hint_text.customer_email_domain'),
            'customer_password.required_if' => __('validation.hint_text.customer_status_2_required'),
            'customer_email_name.required_if' => __('validation.hint_text.customer_status_2_required'),
            'customer_email_domain.required_if' => __('validation.hint_text.customer_status_2_required'),
            'customer_email.required_if' => __('validation.hint_text.customer_status_2_required'),
            'customer_kana01.regex'=>__('validation.hint_text.customer_kana'),
            'customer_kana02.regex'=>__('validation.hint_text.customer_kana'),
            'customer_phone_number01.regex'=>__('validation.hint_text.customer_phone_number'),
            'customer_phone_number02.regex'=>__('validation.hint_text.customer_phone_number'),
            'customer_zipcode.regex'=>__('validation.hint_text.customer_zipcode'),
        ];

        if(isset($params['customer_email_name']) && isset($params['customer_email_domain']))
            $params['customer_email'] = $params['customer_email_name']."@".$params['customer_email_domain'];

        $validator = Validator::make($params, [
            'customer_status' => ['required', 'exists:customer_statuses,id'],
            'customer_wholesale_flag' => ['required', 'integer', 'in:0,1'],
            'customer_email_name'=>['nullable', 'required_if:customer_status,2', 'string', "regex:/^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+$/", 'max:100'],
            'customer_email_domain'=>['nullable', 'required_if:customer_status,2', 'string', 'regex:/^([a-zA-Z0-9]([a-zA-Z0-9\-]{0,61}[a-zA-Z0-9])?\.)+[a-zA-Z]{2,6}$/'],
            'customer_email'=>['nullable', 'required_if:customer_status,2', 'email', 'unique:customers,email'],
            'customer_password' => ['nullable', 'required_if:customer_status,2', 'string', 'regex:/^(?=.*?[a-zA-Z])(?=.*?\d)[a-zA-Z0-9!#$%&()*+,.:;=?@\[\]^_{}-]+$/', 'min:8'],
            'customer_name01'=>['required', 'string', 'max:255'],
            'customer_name02'=>['required', 'string', 'max:255'],
            'customer_kana01'=>['required', 'string', 'regex:/^[ァ-ヶー]+$/u', 'max:255'],
            'customer_kana02'=>['required', 'string', 'regex:/^[ァ-ヶー]+$/u', 'max:255'],
            'customer_phone_number01'=>['required', 'string', 'regex:/^[0-9]+$/', 'max:11'],
            'customer_phone_number02'=>['nullable', 'string', 'regex:/^[0-9]+$/', 'max:11'],
            'customer_zipcode'=>['required', 'string', 'regex:/^[0-9]+$/', 'min:7', 'max:7'],
            'customer_prefecture'=>['required', 'exists:prefectures,id'],
            'customer_address1'=>['required', 'string', 'max:255'],
            'customer_address2'=>['required', 'string', 'max:255'],
            'customer_address3'=>['nullable', 'string', 'max:255'],
            'customer_advertising_media_code'=>['nullable', 'exists:advertising_media,code'],
            'customer_birthday'=>['required', 'string', 'date_format:Y-m-d'],
            'customer_no_phone_call'=>['nullable', 'integer', 'min:1', 'max:1'],
            'customer_mail_flag'=>['required', 'in:2,3'],
            'customer_direct_mail_flag'=>['required', 'in:1,2'],
            'shop_memo_note'=>['nullable', 'string'],
            'shop_memo_important'=>['nullable', 'integer', 'min:1', 'max:1'],
            'shop_memo_claim_flag'=>['nullable', 'integer', 'min:1', 'max:1'],
        ], $messages);

        $is_popup = isset($params['isPopup']);
        if (!$is_popup) {
            if ($validator->fails()) {
                $this->toastr->add('error', implode(" ",$validator->messages()->all()));
                return Redirect::back()->withErrors($validator)->withInput();
            }

            try {
                $customer = $this->service->createSave($params);
                $this->toastr->add('success',__("common.response.success"));
            } catch (\Exception $e) {
                $this->toastr->add('error', $e->getMessage());
                return Redirect::back()->withInput();
            }


            return redirect()->route('admin.customer.info', ['id' => $customer->id]);
        }
        else {
            $responseData = new ApiResponseData($request);

            if ($validator->fails()) {
                $responseData->message = implode(" ",$validator->messages()->all());
                $responseData->saved = $validator->messages();
                $responseData->status = "error";
                return response()->json($responseData);
            }

            try {
                $customer = $this->service->createSave($params);
                $customer = Customer::with('shopMemos')->find($customer->id);
                $customer->shopMemos()
                    ->orderBy('important', 'desc')
                    ->orderBy('claim_flag', 'desc')
                    ->orderBy('created_at', 'desc');
                $responseData->saved = $customer->toJson();
                $responseData->message = __("common.response.success");
                $responseData->status = "success";
            } catch (\Exception $e) {
                $responseData->message = $e->getMessage();
                $responseData->status = "error";
            }

            return response()->json($responseData);
        }
    }

    public function update(Request $request, $id)
    {
        $params = $request->all();
        $params["id"] = $id;
        //半角英数記号のみ許可
        $messages = [
            'updated_at.exists' => __('validation.hint_text.aleady_modified'),
            'customer_password.regex' => __('validation.hint_text.alpha_num_symbol_both'),
            'customer_email_name.regex'=>__('validation.hint_text.customer_email_domain'),
            'customer_email_domain.regex'=>__('validation.hint_text.customer_email_domain'),
            'customer_password.required_if' => __('validation.hint_text.customer_status_2_required'),
            'customer_email_name.required_if' => __('validation.hint_text.customer_status_2_required'),
            'customer_email_domain.required_if' => __('validation.hint_text.customer_status_2_required'),
            'customer_email.required_if' => __('validation.hint_text.customer_status_2_required'),
            'customer_kana01.regex'=>__('validation.hint_text.customer_kana'),
            'customer_kana02.regex'=>__('validation.hint_text.customer_kana'),
            'customer_phone_number01.regex'=>__('validation.hint_text.customer_phone_number'),
            'customer_phone_number02.regex'=>__('validation.hint_text.customer_phone_number'),
            'customer_zipcode.regex'=>__('validation.hint_text.customer_zipcode'),
        ];

        if(isset($params['customer_email_name']) && isset($params['customer_email_domain']))
            $params['customer_email'] = $params['customer_email_name']."@".$params['customer_email_domain'];

        $validator = Validator::make($params, [
            'id'=>['required', 'exists:customers,id'],
            'updated_at' => ['required',  'exists:customers,updated_at,id,'.$params['id']],
            'customer_status' => ['required', 'exists:customer_statuses,id'],
            'customer_wholesale_flag' => ['required', 'integer', 'in:0,1'],
            'customer_email_name'=>['nullable', 'required_if:customer_status,==,2', 'string', "regex:/^[a-zA-Z0-9!#$%&()*+,.:;=?@\[\]^_{}-]+$/", 'max:100'],
            'customer_email_domain'=>['nullable', 'required_if:customer_status,==,2', 'string', 'regex:/^([a-zA-Z0-9]([a-zA-Z0-9\-]{0,61}[a-zA-Z0-9])?\.)+[a-zA-Z]{2,6}$/'],
            'customer_email'=>['nullable', 'required_if:customer_status,==,2', 'email', "unique:customers,email,".$params['id']],
            'customer_password' => ['nullable', 'required_if:customer_status,==,2', 'string', 'regex:/^(?=.*?[a-zA-Z])(?=.*?\d)[a-zA-Z0-9!#$%&()*+,.:;=?@\[\]^_{}-]+$/', 'min:8'],
            'customer_name01'=>['required', 'string', 'max:255'],
            'customer_name02'=>['required', 'string', 'max:255'],
            'customer_kana01'=>['required', 'string', 'regex:/^[ァ-ヶー]+$/u', 'max:255'],
            'customer_kana02'=>['required', 'string', 'regex:/^[ァ-ヶー]+$/u', 'max:255'],
            'customer_phone_number01'=>['required', 'string', 'regex:/^[0-9]+$/', 'max:11'],
            'customer_phone_number02'=>['nullable', 'string', 'regex:/^[0-9]+$/', 'max:11'],
            'customer_zipcode'=>['required', 'string', 'regex:/^[0-9]+$/', 'min:7', 'max:7'],
            'customer_prefecture'=>['required', 'exists:prefectures,id'],
            'customer_address1'=>['required', 'string', 'max:255'],
            'customer_address2'=>['required', 'string', 'max:255'],
            'customer_address3'=>['nullable', 'string', 'max:255'],
            'customer_advertising_media_code'=>['nullable', 'exists:advertising_media,code'],
            'customer_birthday'=>['required', 'string', 'date_format:Y-m-d'],
            'customer_no_phone_call'=>['nullable', 'integer', 'min:1', 'max:1'],
            'customer_mail_flag'=>['required', 'in:2,3'],
            'customer_direct_mail_flag'=>['required', 'in:1,2'],
            'shop_memo_note'=>['required', 'string'],
            'shop_memo_important'=>['nullable', 'integer', 'min:1', 'max:1'],
            'shop_memo_claim_flag'=>['nullable', 'integer', 'min:1', 'max:1'],
        ], $messages);


        if ($validator->fails()) {
            $this->toastr->add('error', implode(" ",$validator->messages()->all()));
            return Redirect::back()->withErrors($validator)->withInput();
        }

        try {
            $this->service->update($params);
            $this->toastr->add('success',__("common.response.success"));
        } catch (\Exception $e) {
            $this->toastr->add('error', $e->getMessage());
            return Redirect::back()->withInput();
        }

        return Redirect::back();
    }

    public function delete(Request $request)
    {
        $params = $request->all();
        $validator = Validator::make($params, [
            'id'=>['required', 'exists:customers,id'],
        ]);

        $responseData = new ApiResponseData($request);

        if ($validator->fails()) {
            $responseData->message = implode(" ",$validator->messages()->all());
            $responseData->saved = $validator->messages();
            $responseData->status = "error";
            return response()->json($responseData);
        }

        try {
            $this->service->delete($params["id"]);
            $responseData->message = __("common.response.success");
            $responseData->status = "success";
        } catch (\Exception $e) {
            $responseData->message = $e->getMessage();
            $responseData->status = "error";
        }

        return response()->json($responseData);
    }
}
