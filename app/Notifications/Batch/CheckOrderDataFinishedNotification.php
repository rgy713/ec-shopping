<?php

namespace App\Notifications\Batch;

use App\Common\SystemMessage;
use App\Notifications\OperationAdminNotification;
use Illuminate\Notifications\Messages\MailMessage;

class CheckOrderDataFinishedNotification extends OperationAdminNotification
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
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * @inheritDoc
     */
    public function getSystemMessage($notifiable): SystemMessage
    {
        $systemMessage = new SystemMessage();
        $systemMessage->message = "受注データチェックを実施しました。";
        $systemMessage->level = "success";
        return $systemMessage;
    }


}
