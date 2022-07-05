<?php

namespace App\Notifications;

use App\Common\SystemMessage;
use App\Events\PeriodicInfoModifiedByCustomer;
use App\Mail\TemplateMail;

class PeriodicInfoModifiedNotification extends OperationAdminNotification
{
    /**
     * @var PeriodicInfoModifiedByCustomer
     */
    protected $event;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(PeriodicInfoModifiedByCustomer $event)
    {
        $this->event=$event;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $templateMail = new TemplateMail(202);//定期情報変更通知メールのID

        return $templateMail->build()
            ->with('periodicModifying',$this->event->getPeriodicOrderOriginal())//埋め込み変数：変更前
            ->with('periodicModified',$this->event->getPeriodicOrderAttributes())//埋め込み変数：変更後
            ->with('shippingModifying',$this->event->getPeriodicShippingOriginal())//埋め込み変数：変更前
            ->with('shippingModified',$this->event->getPeriodicShippingAttributes())//埋め込み変数：変更後
            ->to($notifiable->email);//宛先は$notifiableの通知先
    }

    /**
     * @inheritDoc
     */
    public function getSystemMessage($notifiable): SystemMessage
    {
        $systemMessage = new SystemMessage();
        $systemMessage->message = "定期宅配便(".$this->event->getPeriodicOrder()->id.") 情報がユーザーにより更新されました";
        $systemMessage->level = "info";
        return $systemMessage;
    }


}
