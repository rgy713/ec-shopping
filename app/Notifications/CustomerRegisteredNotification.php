<?php

namespace App\Notifications;

use App\Channels\MailHistoryTableChannel;
use App\Channels\MailHistoryTableChannelNotifiable;
use App\Events\CustomerRegistered;
use App\Mail\TemplateMail;
use App\Models\MailHistory;
use App\Notifications\Interfaces\GetMailTemplateIdInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;


class CustomerRegisteredNotification extends Notification implements MailHistoryTableChannelNotifiable, GetMailTemplateIdInterface
{
    use Queueable;

    /**
     * @var CustomerRegistered
     */
    protected $event;

    /**
     * @var TemplateMail
     */
    protected $templateMail;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(CustomerRegistered $event)
    {
        $this->event = $event;

        //テンプレートメールクラスのインスタンス
        $templateMail = app()->make(TemplateMail::class, ['mailTemplateId' => self::getMailTemplateId()]);

        //インスタンス作成後にbuild()を呼ばないと利用可能な状態にならない。
        $this->templateMail = $templateMail->build()->with('Customer', $event->getRegisteredCustomer());
    }

    /**
     * @inheritDoc
     */
    static public function getMailTemplateId(): int
    {
        return 101;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', MailHistoryTableChannel::class];
    }

    /**
     * @param $notifiable
     * @return TemplateMail
     * @author k.yamamoto@balocco.info
     */
    public function toMail($notifiable)
    {
        //$notifiableの通知先に送信する設定を追加し、Mailableオブジェクトを返す
        return $this->templateMail->to($notifiable->email);
    }

    /**
     * @inheritDoc
     */
    public function toMailHistoryTable($notifiable, Notification $notification): MailHistory
    {
        //mail_histories保存用に顧客IDを取得
        $customerId = $this->event->getRegisteredCustomer()->id;
        //mail_historiesへ保存するためのモデルインスタンスを返す
        return $this->templateMail->createMailHistoryModel($customerId);
    }

}
