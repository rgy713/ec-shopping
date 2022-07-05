<?php

namespace App\Notifications\Batch;

use App\Common\SystemMessage;
use App\Mail\TemplateMail;
use App\Notifications\OperationAdminNotification;

class BatchWarningNotification extends OperationAdminNotification
{

    /**
     * @var string
     */
    protected $batchName;
    /**
     * @var string
     */
    protected $exception;

    /**
     * BatchStartedNotification constructor.
     * @param $batchName
     */
    public function __construct($batchName, $exception)
    {
        $this->batchName=$batchName;
        $this->exception=$exception;
    }

    /**
     * @param $notifiable
     * @return mixed
     * @author k.yamamoto@balocco.info
     */
    public function toMail($notifiable)
    {
        $templateMail = new TemplateMail(1015);//バッチ処理例外メールのID
        return $templateMail->build()
            ->with("name" , $this->batchName)
            ->with("exception" , $this->exception)
            ->to($notifiable->email);//宛先は$notifiableの通知先

    }

    /**
     * @inheritDoc
     */
    public function getSystemMessage($notifiable): SystemMessage
    {
        $systemMessage = new SystemMessage();
        $systemMessage->message = "バッチ処理".$this->batchName. $this->exception;
        $systemMessage->level = "warning";
        return $systemMessage;
    }


}
