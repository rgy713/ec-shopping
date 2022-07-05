<?php

namespace App\Notifications\Batch;

use App\Common\SystemMessage;
use App\Mail\TemplateMail;
use App\Notifications\OperationAdminNotification;

class BatchFailedNotification extends OperationAdminNotification
{

    /**
     * @var string
     */
    protected $batchName;

    protected $errorMessage;

    /**
     * BatchFailedNotification constructor.
     * @param $batchName
     */
    public function __construct($batchName, $errorMessage = null)
    {
        $this->batchName=$batchName;
        $this->errorMessage=$errorMessage;
    }

    /**
     * @param $notifiable
     * @return TemplateMail
     * @author k.yamamoto@balocco.info
     */
    public function toMail($notifiable)
    {
        $templateMail = new TemplateMail(1002);//バッチ処理失敗メールのID
        return $templateMail->build()
            ->with("errorMessage", $this->errorMessage)
            ->to($notifiable->email);//宛先は$notifiableの通知先

    }

    /**
     * @inheritDoc
     */
    public function getSystemMessage($notifiable): SystemMessage
    {
        $systemMessage = new SystemMessage();
        $systemMessage->message = "バッチ処理失敗 ". $this->batchName . $this->errorMessage;
        $systemMessage->level = "danger";
        return $systemMessage;
    }


}
