<?php

namespace App\Notifications\Batch;

use App\Channels\MailHistoryTableChannel;
use App\Channels\MailHistoryTableChannelNotifiable;
use App\Events\Batch\AutoMailTargetOrderFound;
use App\Mail\TemplateMail;
use App\Models\MailHistory;
use App\Notifications\Interfaces\GetMailTemplateIdInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * TODO:実装
 * Class SendAutoMailNotification
 * @package App\Notifications\Batch
 * @author k.yamamoto@balocco.info
 */
class SendAutoMailNotification extends Notification implements MailHistoryTableChannelNotifiable
{
    use Queueable;

    protected $event;

    /**
     * @var TemplateMail
     */
    protected $templateMail;

    /**
     * SendAutoMailNotification constructor.
     * @param AutoMailTargetOrderFound $event
     */
    public function __construct(AutoMailTargetOrderFound $event)
    {
        //
        $this->event = $event;
        $templateMail = app()->make(TemplateMail::class, ['mailTemplateId' => $this->event->mailTemplateId]);
        $this->templateMail = $templateMail->build()->with('order', $this->event->order);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', MailHistoryTableChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     * @param $notifiable
     * @return TemplateMail
     */
    public function toMail($notifiable)
    {

        return $this->templateMail->to($notifiable->email);
    }

    /**
     * @param $notifiable
     * @param Notification $notification
     * @return MailHistory
     * @throws \Throwable
     */
    public function toMailHistoryTable($notifiable, Notification $notification): MailHistory
    {

        //mail_historiesへ保存するためのモデルインスタンスを返す
        return $this->templateMail->createMailHistoryModel($this->event->order->customer_id, $this->event->order->id);

    }
}
