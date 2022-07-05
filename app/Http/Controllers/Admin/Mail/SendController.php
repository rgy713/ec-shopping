<?php

namespace App\Http\Controllers\Admin\Mail;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\MailHistoryService;
use App\Services\FlashToastrMessageService;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use App\Common\Api\ApiResponseData;

class SendController extends Controller
{
    private $service;
    private $toastr;

    /**
     * AccountController constructor.
     */
    public function __construct()
    {
        $this->service = app(MailHistoryService::class);
        $this->toastr = app(FlashToastrMessageService::class);
    }

    public function order($id)
    {
        $params = ['id'=>$id];
        $validator = Validator::make($params, [
            'id' => ['required', 'exists:orders,id'],
        ]);

        if ($validator->fails()) {
            $this->toastr->add('error', implode(" ",$validator->messages()->all()));
            return Redirect::back()->withErrors($validator)->withInput();
        }
        // email列がNULLの場合、ステータスコード404を返す。
        $order = app(Order::class)->find($id);
        if(!isset($order->email))
            return abort(404);

        $mails = $this->service->getMailHistory($id);
        return view("admin.pages.mail.send", compact('mails'));
    }

    public function getMailTemplate(Request $request)
    {
        $params = $request->all();
        $validator = Validator::make($params, [
            'mail_template_id' => ['required', 'exists:mail_templates,id'],
        ]);

        $responseData = new ApiResponseData($request);

        if ($validator->fails()) {
            $responseData->message = implode(" ",$validator->messages()->all());
            $responseData->saved = $validator->messages();
            $responseData->status = "error";
            return response()->json($responseData);
        }

        try {
            $responseData->saved = $this->service->getMailTemplate($params['mail_template_id']);
            $responseData->message = __("common.response.success");
            $responseData->status = "success";
        } catch (\Exception $e) {
            $responseData->message = $e->getMessage();
            $responseData->status = "error";
        }

        return response()->json($responseData);
    }

    public function sendMail(Request $request, $id)
    {
        $params = $request->all();
        $params["id"] = $id;
        $validator = Validator::make($params, [
            'id' => ['required', 'exists:orders,id'],
            'mail_template_id' => ['required', 'exists:mail_templates,id'],
            'subject' => ['required', 'string', 'max:50'],
        ]);

        if ($validator->fails()) {
            $this->toastr->add('error', implode(" ",$validator->messages()->all()));
            return Redirect::back()->withErrors($validator)->withInput();
        }

        try {
            $this->service->sendMail($params);
            $this->toastr->add('success',__("common.response.success"));
        } catch (\Exception $e) {
            $this->toastr->add('error', $e->getMessage());
        }


        return Redirect::back();
    }
}
