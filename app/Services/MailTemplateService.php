<?php
/**
 * Created by PhpStorm.
 * User: rgy
 * Date: 4/18/2019
 * Time: 9:50 AM
 */

namespace App\Services;

use App\Common\SystemSetting;
use App\Mail\PreviewTemplateMail;
use App\Models\Customer;
use App\Models\MailTemplate;
use App\Models\MailLayout;
use App\Models\Order;
use App\Models\PeriodicOrder;
use App\Models\PeriodicShipping;
use Illuminate\Support\Facades\Mail;

class MailTemplateService
{
    /**
     * @return mixed
     */
    public function systemMailTemplates()
    {
        $templates = MailTemplate::where('mail_template_type_id', 1)->get();
        return $templates;
    }

    /**
     * @return mixed
     */
    public function stepMailTemplates()
    {
        $templates = MailTemplate::where('mail_template_type_id', 2)->get();
        return $templates;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getTemplate($id)
    {
        return MailTemplate::find($id);
    }

    /**
     * @param $params
     */
    public function update($params)
    {
        $template = MailTemplate::find($params["id"]);
        $template->name = $params["mail_template_name"];
        $template->mail_layout_id = $params["mail_layout_id"];
        $template->subject = $params["mail_template_subject"];
        $template_body_path = storage_path('app/views/' . str_replace('.', '/', $template->body_file_path)) . '.blade.php';
        file_put_contents($template_body_path, $params["mail_template_body"]);
        $template->save();
    }

    public function preview($params)
    {
        $systemSetting = app(SystemSetting::class);
        $template_body_path = storage_path('app/views/mail/templates/preview.blade.php');
        file_put_contents($template_body_path, $params["mail_template_body"]);
        $previewMail = app()->make(PreviewTemplateMail::class, ['mailLayoutId' => $params["mail_layout_id"], 'subject'=>$params["mail_template_subject"], 'mailBodyFilePath'=>'mail.templates.preview']);
        $previewMail->build()
            ->with('order', app(Order::class)->find(1))
            ->with('Customer', app(Customer::class)->find(1))
            ->with('modifying', app(Customer::class)->find(1))
            ->with('modified', app(Customer::class)->find(1))
            ->with('periodicModifying', app(PeriodicOrder::class)->find(1))//埋め込み変数：変更前
            ->with('periodicModified', app(PeriodicOrder::class)->find(1))//埋め込み変数：変更後
            ->with('shippingModifying',app(PeriodicShipping::class)->find(1))//埋め込み変数：変更前
            ->with('shippingModified',app(PeriodicShipping::class)->find(1))//埋め込み変数：変更後
        ;
        Mail::to($systemSetting->operationAdminMailAddress())->send($previewMail);
    }

    public function create($params)
    {
        $max_id = MailTemplate::select("id")->orderBy("id", "DESC")->first();
        $template = new MailTemplate();
        $template->id = $max_id->id + 1;
        $template->name = $params["mail_template_name"];
        $template->mail_layout_id = $params["mail_layout_id"];
        $template->mail_template_type_id = $params["type"];
        $template->subject = $params["mail_template_subject"];
        $template->sending_trigger = $params["sending_trigger"];
        $template->body_file_path = "mail.templates.".$template->id;
        $template_body_path = storage_path('app/views/' . str_replace('.', '/', $template->body_file_path)) . '.blade.php';
        file_put_contents($template_body_path, $params["mail_template_body"]);
        $template->save();
    }
}