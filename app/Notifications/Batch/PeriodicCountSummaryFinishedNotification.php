<?php

namespace App\Notifications\Batch;

use App\Common\SystemMessage;
use App\Mail\TemplateMail;
use App\Notifications\OperationAdminNotification;

class PeriodicCountSummaryFinishedNotification extends OperationAdminNotification
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
     * @return TemplateMail
     * @author k.yamamoto@balocco.info
     */
    public function toMail($notifiable)
    {
        $templateMail = new TemplateMail(1012);//定期稼働者推移バッチ処理成功メールID
        return $templateMail->build()->to($notifiable->email);//宛先は$notifiableの通知先
    }

    /**
     * @inheritDoc
     */
    public function getSystemMessage($notifiable): SystemMessage
    {
        $systemMessage = new SystemMessage();
        $systemMessage->message = "定期稼働者推移バッチ処理が完了しました。";
        $systemMessage->level = "success";
        return $systemMessage;
    }


}
