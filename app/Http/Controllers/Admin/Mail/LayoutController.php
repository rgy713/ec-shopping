<?php

namespace App\Http\Controllers\Admin\Mail;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\FlashToastrMessageService;
use App\Services\MailLayoutService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;

class LayoutController extends Controller
{
    protected $service;
    protected $toastr;

    /**
     * LayoutController constructor.
     */
    public function __construct()
    {
        $this->service = app(MailLayoutService::class);
        $this->toastr = app(FlashToastrMessageService::class);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $layouts = $this->service->getLayouts();
        return view("admin.pages.mail.layout", compact('layouts'));
    }

    public function create()
    {
        return view("admin.pages.mail.layout_edit");
    }

    public function createSave(Request $request)
    {
        $params = $request->all();
        $validator = Validator::make($params, [
            'mail_layout_name' => ['required', 'string', 'max:255'],
            'mail_layout_remark' => ['required', 'string', 'max:255'],
            'mail_layout_header' => ['required', 'string'],
            'mail_layout_footer' => ['required', 'string']
        ]);

        if ($validator->fails()) {
            $this->toastr->add('error', implode(" ", $validator->messages()->all()));
            return Redirect::back()->withErrors($validator)->withInput();
        }

        try {
            $this->service->create($params);
            $this->toastr->add('success', __("common.response.success"));
        } catch (\Exception $e) {
            $this->toastr->add('error', $e->getMessage());
        }


        return Redirect::back();
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $params = ["id" => $id];
        $validator = Validator::make($params, [
            'id' => ['required', 'exists:mail_layouts,id'],
        ]);

        if ($validator->fails()) {
            $this->toastr->add('error', implode(" ", $validator->messages()->all()));
            return Redirect::back();
        }

        $layout = $this->service->getLayout($id);
        return view("admin.pages.mail.layout_edit", compact('layout'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function update(Request $request, $id)
    {
        $params = $request->all();
        $params["id"] = $id;
        $validator = Validator::make($params, [
            'id' => ['required', 'exists:mail_layouts,id'],
            'mail_layout_name' => ['required', 'string', 'max:255'],
            'mail_layout_remark' => ['required', 'string', 'max:255'],
            'mail_layout_header' => ['required', 'string'],
            'mail_layout_footer' => ['required', 'string']
        ]);

        if ($validator->fails()) {
            $this->toastr->add('error', implode(" ", $validator->messages()->all()));
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

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function sample()
    {
        return view("mail.sample");
    }
}
