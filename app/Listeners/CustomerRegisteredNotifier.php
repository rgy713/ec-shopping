<?php


namespace App\Listeners;

use App\Events\CustomerRegistered;
use App\Notifications\CustomerRegisteredNotification;
use App\Services\MailService;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Class CustomerRegisteredNotifier
 * @package App\Listeners
 * @author k.yamamoto@balocco.info
 */
class CustomerRegisteredNotifier implements ShouldQueue
{
    use FailedWithQueue;

    /**
     * @var MailService
     */
    protected $mailService;

    /**
     * CustomerRegisteredNotifier constructor.
     */
    public function __construct(MailService $meilService)
    {
        $this->mailService = $meilService;
    }

    /**
     * 会員が登録された時の通知
     * @param CustomerRegistered $event
     * @author k.yamamoto@balocco.info
     */
    public function handle(CustomerRegistered $event)
    {
        //通知しようとしているメールテンプレートIDを取得
        $mailTemplateId = app()->call([CustomerRegisteredNotification::class, 'getMailTemplateId']);

        //メール送信履歴が存在しているかをチェック
        if (!$this->mailService->customerMailHistoryExists($event->getRegisteredCustomer()->id, $mailTemplateId)) {
            //存在していない場合、通知
            $notification = app()->make(CustomerRegisteredNotification::class, ['event' => $event]);

            if($event->getRegisteredCustomer()->routeNotificationForMail()){
                //emailアドレスが登録されている場合のみ、通知を送信
                $event->getRegisteredCustomer()->notify($notification);
            }

        }


    }

}