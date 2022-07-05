<?php

namespace App\Listeners;

use App\Events\OrderConfirmed;
use App\Notifications\OrderConfirmedNotification;
use App\Services\MailService;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderConfirmedNotifier implements ShouldQueue
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
     * Handle the event.
     * @param  OrderConfirmed $event
     * @return void
     */
    public function handle(OrderConfirmed $event)
    {
        //イベント発火時に指定されるフラグ。falseの場合通知しない。
        if ($event->getNotifyFlag()) {

            //通知しようとしているメールテンプレートIDを取得
            $mailTemplateId = app()->call([OrderConfirmedNotification::class, 'getMailTemplateId']);

            //メール送信履歴が存在しているかをチェック
            if (!$this->mailService->orderMailHistoryExists($event->getOrder()->id, $mailTemplateId)) {
                //存在していない場合、通知
                $notification = app()->make(OrderConfirmedNotification::class, ['event' => $event]);
                $event->getCustomer()->notify($notification);
            }
        }
    }
}
