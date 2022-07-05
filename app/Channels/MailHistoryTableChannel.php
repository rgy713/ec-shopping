<?php

namespace App\Channels;

use App\Models\SystemLog;
use Illuminate\Notifications\Notification;

/**
 * Class MailHistoryTableChannel
 * Notification経由でデータベース内のメール履歴テーブルに保存するクラス
 * このチャンネルを利用する通知クラスは、MailHistoryTableChannelNotifiableを実装すること。
 * @package App\Channels
 * @author k.yamamoto@balocco.info
 */
class MailHistoryTableChannel
{
    /**
     * SystemEventChannel constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param $notifiable
     * @param Notification $notification
     * @author k.yamamoto@balocco.info
     */
    public function send($notifiable, Notification $notification)
    {
        if (!method_exists($notification, 'toMailHistoryTable')) {
            throw new \RuntimeException(
                'Notification is missing toMailHistoryTable method.'
            );
        }
        /** @var SystemLog $item */
        $item = $notification->toMailHistoryTable($notifiable, $notification);
        $item->save();
    }

}