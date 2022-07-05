<?php
/**
 * Created by PhpStorm.
 * User: rgy
 * Date: 5/26/2019
 * Time: 7:22 PM
 */

namespace App\Notifications\Batch;

use App\Common\SystemMessage;
use App\Mail\TemplateMail;
use App\Notifications\OperationAdminNotification;

class CreateAspMediaCodeWarningNotification extends OperationAdminNotification
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
        $templateMail = new TemplateMail(1014);//ASP用広告媒体作成バッチ処理警告のメールID
        return $templateMail->build()->to($notifiable->email);//宛先は$notifiableの通知先

    }

    /**
     * @inheritDoc
     */
    public function getSystemMessage($notifiable): SystemMessage
    {
        $systemMessage = new SystemMessage();
        $systemMessage->message = "ASPの広告番号を自動登録警告";
        $systemMessage->level = "warning";
        return $systemMessage;
    }

}
