<?php

namespace App\Notifications\Batch;

use App\Common\SystemMessage;
use App\Mail\TemplateMail;
use App\Notifications\OperationAdminNotification;
use Illuminate\Notifications\Messages\MailMessage;

class StepDirectMailFinishedNotification extends OperationAdminNotification
{

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * @param $notifiable
     * @return mixed
     * @author k.yamamoto@balocco.info
     */
    public function toMail($notifiable)
    {
        $templateMail = new TemplateMail(1009);//ステップDMバッチ処理成功メールID
        return $templateMail->build()->to($notifiable->email);//宛先は$notifiableの通知先
    }

    /**
     * @inheritDoc
     */
    public function getSystemMessage($notifiable): SystemMessage
    {
        $systemMessage = new SystemMessage();
        $systemMessage->message = "ステップDMデータの作成を実施しました。";
        $systemMessage->level = "success";
        return $systemMessage;
    }


}
