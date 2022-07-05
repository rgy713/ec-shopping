<?php

namespace App\Http\Controllers\Admin\Account;

use App\Http\Controllers\Admin\BaseController;
use App\Models\Admin;
use Illuminate\Http\Request;
use App\Services\AdminAccountService;
use App\Services\FlashToastrMessageService;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

/**
 * Class AccountController
 * @package App\Http\Controllers\Admin\Account
 */
class AccountController extends BaseController
{
    private $service;
    private $toastr;

    /**
     * AccountController constructor.
     */
    public function __construct()
    {
        $this->service = app(AdminAccountService::class);
        $this->toastr = app(FlashToastrMessageService::class);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $accounts = $this->service->getAdminList();

        return view("admin.pages.account.list", compact(['accounts']));
    }

    public function create()
    {
        return view("admin.pages.account.create");
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function createSave(Request $request)
    {
        $params = $request->all();
        //半角英数記号のみ許可
        $messages = [
            'account.regex' => __('validation.hint_text.alpha_num_symbol'),
            'password.regex' => __('validation.hint_text.alpha_num_symbol_both'),
        ];

        $validator = Validator::make($params, [
            'name' => ['required', 'string', 'max:255'],
            'account' => ['required', 'string', 'regex:/^[a-zA-Z0-9!#$%&()*+,.:;=?@\[\]^_{}-]+$/i', 'max:255', 'unique:admins,account'],
            'department' => ['required', 'string', 'max:255'],
            'admin_role_id' => ['required', 'exists:admin_roles,id'],
            'password' => ['required', 'string', 'regex:/^(?=.*?[a-zA-Z])(?=.*?\d)[a-zA-Z0-9!#$%&()*+,.:;=?@\[\]^_{}-]+$/', 'min:8'],
            'enabled' => ['required', 'in:true,false'],
        ], $messages);


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


        return Redirect::back();
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function edit(Request $request, $id)
    {
        $params = $request->all();
        $params["id"] = $id;
        $validator = Validator::make($params, [
            'id' => ['required', 'exists:admins,id'],
        ]);

        if ($validator->fails()) {
            $this->toastr->add('error', implode(" ",$validator->messages()->all()));
            return back();
        }

        $account = Admin::find($id);

        return view("admin.pages.account.edit", compact('account'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function editSave(Request $request, $id)
    {
        $params = $request->all();
        $params["id"] = $id;

        //半角英数記号のみ許可
        $messages = [
            'account.regex' => __('validation.hint_text.alpha_num_symbol'),
            'password.regex' => __('validation.hint_text.alpha_num_symbol_both'),
        ];

        $validator = Validator::make($params, [
            'id' => ['required', 'exists:admins,id'],
            'name' => ['required', 'string', 'max:255'],
            'account' => ['required', 'string', 'regex:/^[a-zA-Z0-9!#$%&()*+,.:;=?@\[\]^_{}-]+$/i', 'max:255', 'unique:admins,account,' . $params['id']],
            'department' => ['required', 'string', 'max:255'],
            'admin_role_id' => ['required', 'exists:admin_roles,id'],
            'password' => ['required', 'string', 'regex:/^(?=.*?[a-zA-Z])(?=.*?\d)[a-zA-Z0-9!#$%&()*+,.:;=?@\[\]^_{}-]+$/', 'min:8'],
            'enabled' => ['required', 'in:true,false'],
        ],$messages);


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

        return Redirect::back();
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function disable(Request $request)
    {
        $params = $request->all();
        $validator = Validator::make($params, [
            'id' => ['required', 'exists:admins,id'],
        ]);

        if ($validator->fails()) {
            $this->toastr->add('error', implode(" ",$validator->messages()->all()));
            return Redirect::back();
        }

        $id = $params['id'];

        try {
            $this->service->changeDisable($id);
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
    public function enable(Request $request)
    {
        $params = $request->all();
        $validator = Validator::make($params, [
            'id' => ['required', 'exists:admins,id'],
        ]);

        if ($validator->fails()) {
            $this->toastr->add('error', implode(" ",$validator->messages()->all()));
            return Redirect::back();
        }

        $id = $params['id'];

        try {
            $this->service->changeEnable($id);
            $this->toastr->add('success', __("common.response.success"));
        } catch (\Exception $e) {
            $this->toastr->add('error', $e->getMessage());
        }
        return Redirect::back();
    }

}