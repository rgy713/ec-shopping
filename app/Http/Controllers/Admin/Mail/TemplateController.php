<?php

namespace App\Http\Controllers\Admin\Mail;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\MailTemplateService;
use App\Services\FlashToastrMessageService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;

class TemplateController extends Controller
{
    protected $service;
    protected $toastr;

    public function __construct()
    {
        $this->service = app(MailTemplateService::class);
        $this->toastr = app(FlashToastrMessageService::class);
    }

    public function index()
    {
        $system_templates = $this->service->systemMailTemplates();
        $step_templates = $this->service->stepMailTemplates();
        return view("admin.pages.mail.template", compact(['system_templates', 'step_templates']));
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author k.yamamoto@balocco.info
     */
    public function edit($id)
    {
        $params = ["id" => $id];
        $validator = Validator::make($params, [
            'id' => ['required', 'exists:mail_templates,id'],
        ]);

        if ($validator->fails()) {
            $this->toastr->add('error', implode(" ", $validator->messages()->all()));
            return Redirect::back();
        }

        $template = $this->service->getTemplate($id);

        return view("admin.pages.mail.template_edit", compact('template'));
    }

    /**
     * @param $type
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author k.yamamoto@balocco.info
     */
    public function create($type)
    {
        $params = ["type" => $type];
        $validator = Validator::make($params, [
            ////mail_template_type_id 2 はステップメール
            'type' => ['required', 'integer', 'exists:mail_template_types,id', 'min:2','max:2'],
        ]);

        if ($validator->fails()) {
            $this->toastr->add('error', implode(" ", $validator->messages()->all()));
            return Redirect::back();
        }

        return view("admin.pages.mail.template_edit");
    }

    /**
     * @param Request $request
     * @param $type
     * @return mixed
     */
    public function createSave(Request $request, $type)
    {
        $params = $request->all();
        $params["type"] = $type;
        $validator = Validator::make($params, [
            //mail_template_type_id 2 はステップメール
            'type' => ['required', 'integer', 'exists:mail_template_types,id', 'min:2','max:2'],
            'sending_trigger' => ['required', 'string', 'max:255'],
            'mail_template_name' => ['required', 'string', 'max:255'],
            'mail_layout_id' => ['required', 'exists:mail_layouts,id'],
            'mail_template_subject' => ['required', 'string', 'max:255'],
            'mail_template_body' => ['required', 'string']
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
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function update(Request $request, $id)
    {
        $params = $request->all();
        $params["id"] = $id;
        $validator = Validator::make($params, [
            'id' => ['required', 'exists:mail_templates,id'],
            'mail_template_name' => ['required', 'string', 'max:255'],
            'mail_layout_id' => ['required', 'exists:mail_layouts,id'],
            'mail_template_subject' => ['required', 'string', 'max:255'],
            'mail_template_body' => ['required', 'string']
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
     * @param Request $request
     * @return mixed
     */
    public function preview(Request $request)
    {
        $params = $request->all();
        $validator = Validator::make($params, [
            'mail_template_name' => ['required', 'string', 'max:255'],
            'mail_layout_id' => ['required', 'exists:mail_layouts,id'],
            'mail_template_subject' => ['required', 'string', 'max:255'],
            'mail_template_body' => ['required', 'string']
        ]);

        if ($validator->fails()) {
            $this->toastr->add('error', implode(" ", $validator->messages()->all()));
            return Redirect::back()->withErrors($validator)->withInput();
        }

        try {
            $this->service->preview($params);
            $this->toastr->add('success', __("common.response.success"));
        } catch (\Exception $e) {
            $this->toastr->add('error', $e->getMessage());
        }


        return Redirect::back()->withInput();

    }
}
