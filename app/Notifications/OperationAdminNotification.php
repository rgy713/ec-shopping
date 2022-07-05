<?php

namespace App\Notifications;

use App\Channels\SystemLogTableChannel;
use App\Channels\SystemLogTableChannelNotifiable;
use App\Common\OperationAdministrator;
use App\Common\SystemMessage;
use App\Models\SystemLog;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/**
 * 運用管理者向け通知の基底クラス。
 * Class OperationAdminNotification
 * @package App\Notifications
 * @author k.yamamoto@balocco.info
 */
abstract class OperationAdminNotification extends Notification implements SystemLogTableChannelNotifiable
{
    use Queueable;

    /**
     * @param $notifiable
     * @return array
     * @author k.yamamoto@balocco.info
     */
    public function via($notifiable)
    {

        if (get_class($notifiable) === OperationAdministrator::class) {
            //運用管理者の場合、共通のシステムログテーブルへの通知も行う
            return [SystemLogTableChannel::class, 'mail'];
        } else {
            //それ以外の場合、メール通知
            return ['mail'];
        }
    }

    /**
     * 運営管理者向けシステムログテーブルへの通知処理。
     * @param $notifiable
     * @param Notification $notification
     * @return SystemLog
     * @author k.yamamoto@balocco.info
     */
    public function toSystemLogTable($notifiable, Notification $notification): SystemLog
    {
        $item = new SystemLog();
        $item->id = $notification->id;
        $item->type = get_class($notification);
        $item->data = $notification->getSystemMessage($notifiable)->toArray();
        $item->read_at = null;
        return $item;
    }

    /**
     * システムログテーブルへ通知する内容を返す
     * @param $notifiable
     * @return SystemMessage
     * @author k.yamamoto@balocco.info
     */
    abstract public function getSystemMessage($notifiable) : SystemMessage;

}