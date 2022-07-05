<?php

namespace App\Listeners;

use App\Events\Interfaces\GetOrder;
use App\Notifications\ShippingScheduledNotification;
use App\Services\MailService;
use Illuminate\Contracts\Queue\ShouldQueue;

class ShippingScheduledNotifier implements ShouldQueue
{
    use FailedWithQueue;

    /**
     * @var MailService
     */
    protected $mailService;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(MailService $meilService)
    {
        $this->mailService = $meilService;
    }

    /**
     * @param GetOrder $event
     * @author k.yamamoto@balocco.info
     */
    public function handle(GetOrder $event)
    {
        //通知しようとしているメールテンプレートIDを取得
        $mailTemplateId = app()->call([ShippingScheduledNotification::class, 'getMailTemplateId']);

        //メール送信履歴が存在しているかをチェック
        if (!$this->mailService->orderMailHistoryExists($event->getOrder()->id, $mailTemplateId)) {
            //存在していない場合、通知
            $notification = app()->make(ShippingScheduledNotification::class, ['event' => $event]);
            $event->getOrder()->customer->notify($notification);
        }
    }
}
