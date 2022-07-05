<?php

namespace App\Notifications;

use App\Common\SystemMessage;
use App\Events\Interfaces\AddressModified;
use App\Mail\TemplateMail;

class CustomerAddressModifiedNotification extends OperationAdminNotification
{
    /**
     * @var AddressModified
     */
    protected $event;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(AddressModified $event)
    {
        $this->event = $event;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $templateMail = new TemplateMail(201);//住所変更通知メールのID

        return $templateMail->build()
            ->with('modifying', $this->event->getModifying())//埋め込み変数：変更前
            ->with('modified', $this->event->getModified())//埋め込み変数：変更後
            ->to($notifiable->email);//宛先は$notifiableの通知先
    }

    /**
     * @inheritDoc
     */
    public function getSystemMessage($notifiable): SystemMessage
    {
        $arrayCustomer = $this->event->getModified();
        $systemMessage = new SystemMessage();
        $systemMessage->message = "顧客情報(ID:" .$arrayCustomer['id']. ") がユーザーにより更新されました";
        $systemMessage->level = "info";
        return $systemMessage;
    }


}
