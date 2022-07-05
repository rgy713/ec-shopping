<?php


namespace App\Http\Controllers\Admin\Mail;

use App\Http\Controllers\Controller;
use App\Services\FlashToastrMessageService;
use App\Services\MailTriggerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;

class TriggerController extends Controller
{
    protected $service;
    protected $toastr;

    public function __construct()
    {
        $this->service = app(MailTriggerService::class);
        $this->toastr = app(FlashToastrMessageService::class);
    }

    public function index($id)
    {
        $params = ["id" => $id];
        $validator = Validator::make($params, [
            'id' => ['required', 'exists:mail_templates,id'],
        ]);

        if ($validator->fails()) {
            $this->toastr->add('error', implode(" ", $validator->messages()->all()));
            return Redirect::back();
        }

        $trigger = $this->service->getTrigger($id);
        $lineups = [];
        if(isset($trigger))
            $lineups = $this->service->getItemLineups($trigger->id);
        return view("admin.pages.mail.trigger", compact(['trigger','lineups']));
    }

    public function create(Request $request, $id)
    {
        $params = $request->all();
        $params["id"] = $id;
        $validator = Validator::make($params, [
            'id' => ['required', 'exists:mail_templates,id'],
            'mail_setting_enabled' => ['required', 'in:TRUE,FALSE'],
            'mail_setting_order_method' => ['required', 'integer', 'min:1', 'max:4'],
            'item_linup_id'=>['nullable', 'array'],
            'item_linup_id.*'=>['required_with:item_linup_id', 'exists:item_lineups,id'],
            'elapsed_days'=>['required', 'integer', 'min:1' , 'max:32767'],
            'first_purchase_only_flag'=>['required', 'in:TRUE,FALSE'],
            'regular_member_only_flag'=>['required', 'in:TRUE,FALSE'],
            'customer_mail_magazine_flag'=>['required', 'in:TRUE,FALSE'],
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
}