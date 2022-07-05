<?php

namespace App\Http\Controllers\Admin\Mail;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\SystemSettingService;
use App\Services\FlashToastrMessageService;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;



class SystemController extends Controller
{
    private $service;
    private $toastr;

    public function __construct()
    {
        $this->service = app(SystemSettingService::class);
        $this->toastr = app(FlashToastrMessageService::class);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $system_setting = $this->service->getSystemSetting();
        return view("admin.pages.mail.system", compact('system_setting'));
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function update(Request $request)
    {
        $params = $request->all();
        $messages = [
            'system_sender_mail_address.regex' => __('validation.email'),
            'system_admin_mail_address.regex' => __('validation.email'),
            'operation_admin_mail_address.regex' => __('validation.email'),
        ];
        $validator = Validator::make($params, [
            'id' => ['required', 'exists:system_settings,id'],
            'system_sender_mail_address' => ['required', 'email', "regex:/^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/", 'max:255'],
            'system_sender_signature' => ['required', 'string', 'max:255'],
            'system_admin_mail_address' => ['required', 'email', "regex:/^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/", 'max:255'],
            'operation_admin_mail_address' => ['required', 'email', "regex:/^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/", 'max:255']
        ], $messages);


        if ($validator->fails()) {
            $this->toastr->add('error', implode(" ",$validator->messages()->all()));
            return Redirect::back()->withErrors($validator)->withInput();
        }

        try {
            $this->service->update($params);
            $this->toastr->add('success', __("common.response.success"));
        } catch (\Exception $e) {
            $this->toastr->add('error', $e->getMessage());
        }

        return Redirect::back();
    }
}
