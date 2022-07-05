<?php

namespace App\Notifications\Batch;

use App\Common\SystemMessage;
use App\Events\Batch\AggregatePortfolioFinished;
use App\Mail\TemplateMail;
use App\Notifications\OperationAdminNotification;

class AggregatePortfolioFinishedNotification extends OperationAdminNotification
{

    /**
     * @var AggregatePortfolioFinished
     */
    protected $event;


    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(AggregatePortfolioFinished $event)
    {
        $this->event = $event;
    }

    /**
     * @param $notifiable
     * @return TemplateMail
     * @author k.yamamoto@balocco.info
     */
    public function toMail($notifiable)
    {
        $templateMail = new TemplateMail(1003);//ポートフォリオバッチ完了メールのID
        return $templateMail->build()->to($notifiable->email);//宛先は$notifiableの通知先
    }

    /**
     * @inheritDoc
     */
    public function getSystemMessage($notifiable): SystemMessage
    {
        $systemMessage = new SystemMessage();
        $systemMessage->message = "ポートフォリオバッチ処理が完了しました。";
        $systemMessage->level = "success";
        return $systemMessage;
    }


}
