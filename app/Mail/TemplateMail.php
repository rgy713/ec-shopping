<?php

namespace App\Mail;

use App\Common\SystemSetting;
use App\Models\MailHistory;
use App\Models\MailTemplate;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * mail_templates テーブルの内容を送信する
 * Class TemplateMail
 * @package App\Mail
 * @author k.yamamoto@balocco.info
 */
class TemplateMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var MailTemplate
     */
    protected $mailTemplate;

    /**
     * @var SystemSetting
     */
    protected $systemSetting;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($mailTemplateId, $mailSubject=null)
    {
        $model = app(MailTemplate::class);
        $this->systemSetting = app(SystemSetting::class);
        $this->mailTemplate = $model->find($mailTemplateId);
        if($mailSubject)
            $this->mailTemplate->subject = $mailSubject;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->text('mail.template_mail')
            ->with('headerFilePath', $this->mailTemplate->layout->header_file_path)
            ->with('bodyFilePath', $this->mailTemplate->body_file_path)
            ->with('footerFilePath', $this->mailTemplate->layout->footer_file_path)
            ->from($this->systemSetting->systemSenderMailAddress(), $this->systemSetting->systemSenderSignature())
            ->subject($this->mailTemplate->subject);
    }

    /**
     * メールの本文を文字列として返す
     * @return string
     * @throws \Throwable
     * @author k.yamamoto@balocco.info
     */
    public function getMailBody()
    {
        return view($this->textView, $this->buildViewData())->render();
    }

    /**
     * mail_histories保存用のオブジェクトを作成する。
     * @param $customerId
     * @param null $orderId
     * @return MailHistory
     * @throws \Throwable
     * @author k.yamamoto@balocco.info
     */
    public function createMailHistoryModel($customerId, $orderId = null)
    {
        $model = app(MailHistory::class);

        $model->customer_id = $customerId;
        if (!is_null($orderId)) {
            $model->order_id = $orderId;
        }
        $model->mail_template_id = $this->mailTemplate->id;
        $model->body = $this->getMailBody();
        $model->subject = $this->mailTemplate->subject;
        return $model;
    }
}
