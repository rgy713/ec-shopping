<?php

namespace App\Listeners;

use App\Events\Interfaces\GetOrder;
use App\Notifications\OrderShippedNotification;
use App\Services\MailService;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderShippedNotifier implements ShouldQueue
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
        $mailTemplateId = app()->call([OrderShippedNotification::class, 'getMailTemplateId']);

        //メール送信履歴が存在しているかをチェック
        if (!$this->mailService->orderMailHistoryExists($event->getOrder()->id, $mailTemplateId)) {
            //存在していない場合、通知
            $notification = app()->make(OrderShippedNotification::class, ['event' => $event]);
            $event->getOrder()->customer->notify($notification);
        }
    }
}
