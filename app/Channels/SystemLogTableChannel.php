<?php

namespace App\Channels;

use App\Models\SystemLog;
use Illuminate\Notifications\Notification;

/**
 * Class SystemLogTableChannel
 * Notification経由でデータベース内のシステムログテーブルに保存するクラス
 * このチャンネルを利用する通知クラスは、SystemLogTableChannelNotifiableを実装すること。
 * @package App\SystemEventChannel
 * @author k.yamamoto@balocco.info
 */
class SystemLogTableChannel
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
        if (!method_exists($notification, 'toSystemLogTable')) {
            throw new \RuntimeException(
                'Notification is missing toSystemLogTable method.'
            );
        }
        /** @var SystemLog $item */
        $item = $notification->toSystemLogTable($notifiable, $notification);
        $item->save();
    }

}