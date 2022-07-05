<?php

namespace App\Listeners\Batch;

use App\Events\Batch\AutoMailTargetOrderFound;
use App\Listeners\FailedWithQueue;
use App\Notifications\Batch\SendAutoMailNotification;
use App\Services\MailService;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Class SendAutoMailNotifier
 * @package App\Listeners\Batch
 */
class SendAutoMailNotifier implements ShouldQueue
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
     * TODO:実装
     * @param  AutoMailTargetOrderFound  $event
     * @return void
     */
    public function handle(AutoMailTargetOrderFound $event)
    {
        if (!$this->mailService->orderMailHistoryExists($event->order->id, $event->mailTemplateId)) {
            $notification = app()->make(SendAutoMailNotification::class, ['event' => $event]);
            $event->order->notify($notification);
        }
    }
}
