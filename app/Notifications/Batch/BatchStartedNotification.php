<?php

namespace App\Notifications\Batch;

use App\Common\SystemMessage;
use App\Mail\TemplateMail;
use App\Notifications\OperationAdminNotification;

class BatchStartedNotification extends OperationAdminNotification
{

    /**
     * @var string
     */
    protected $batchName;

    /**
     * BatchStartedNotification constructor.
     * @param $batchName
     */
    public function __construct($batchName)
    {
        $this->batchName=$batchName;
    }

    /**
     * @param $notifiable
     * @return mixed
     * @author k.yamamoto@balocco.info
     */
    public function toMail($notifiable)
    {
        $templateMail = new TemplateMail(1001);//バッチ処理開始メールのID
        return $templateMail->build()->to($notifiable->email);//宛先は$notifiableの通知先

    }

    /**
     * @inheritDoc
     */
    public function getSystemMessage($notifiable): SystemMessage
    {
        $systemMessage = new SystemMessage();
        $systemMessage->message = "バッチ処理開始".$this->batchName;
        $systemMessage->level = "info";
        return $systemMessage;
    }


}
