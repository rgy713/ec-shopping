<?php


namespace App\Notifications;

use App\Common\SystemMessage;
use App\Events\JobFailed;
use App\Models\SystemLog;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;


class JobFailedNotification extends Notification
{
    /**
     * @var JobFailed
     */
    public $event;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(JobFailed $event)
    {
        $this->event = $event;
    }

    /**
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage())
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

}