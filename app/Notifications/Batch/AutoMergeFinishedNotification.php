<?php

namespace App\Notifications\Batch;

use App\Common\SystemMessage;
use App\Mail\TemplateMail;
use App\Notifications\OperationAdminNotification;

class AutoMergeFinishedNotification extends OperationAdminNotification
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
        $templateMail = new TemplateMail(1004);//自動統合バッチ処理完了メールのID
        return $templateMail->build()->to($notifiable->email);//宛先は$notifiableの通知先

    }

    /**
     * @inheritDoc
     */
    public function getSystemMessage($notifiable): SystemMessage
    {
        $systemMessage = new SystemMessage();
        $systemMessage->message = "自動統合バッチ処理が完了しました。";
        $systemMessage->level = "success";
        return $systemMessage;
    }


}
