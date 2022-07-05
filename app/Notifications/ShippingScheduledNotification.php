<?php

namespace App\Notifications;

use App\Channels\MailHistoryTableChannel;
use App\Channels\MailHistoryTableChannelNotifiable;
use App\Events\Interfaces\GetOrder;
use App\Mail\TemplateMail;
use App\Models\MailHistory;
use App\Notifications\Interfaces\GetMailTemplateIdInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ShippingScheduledNotification extends Notification implements MailHistoryTableChannelNotifiable, GetMailTemplateIdInterface
{
    use Queueable;

    /**
     * @var GetOrder
     */
    protected $event;

    /**
     * @var TemplateMail
     */
    protected $templateMail;

    /**
     * ShippingScheduledNotification constructor.
     * @param GetOrder $event
     */
    public function __construct(GetOrder $event, $mailSubject=null)
    {
        $this->event = $event;

        //テンプレートメールクラスのインスタンス
        $templateMail = app()->make(TemplateMail::class, ['mailTemplateId' => self::getMailTemplateId(),'mailSubject'=>$mailSubject]);

        //インスタンス作成後にbuild()を呼ばないと利用可能な状態にならない。
        $this->templateMail = $templateMail->build()->with('order', $event->getOrder());

    }

    /**
     * @inheritDoc
     */
    static public function getMailTemplateId(): int
    {
        return 4;
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
        return $this->templateMail->to($notifiable->email);
    }

    /**
     * @inheritDoc
     */
    public function toMailHistoryTable($notifiable, Notification $notification): MailHistory
    {
        //mail_histories保存用に受注情報を取得
        $order = $this->event->getOrder();

        //mail_historiesへ保存するためのモデルインスタンスを返す
        return $this->templateMail->createMailHistoryModel($order->customer_id, $order->id);

    }


}
