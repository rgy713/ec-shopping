<?php


namespace App\Channels;


use App\Models\MailHistory;
use Illuminate\Notifications\Notification;

interface MailHistoryTableChannelNotifiable
{
    /**
     * mail_historiesテーブルへ保存する内容を返す
     * @param $notifiable
     * @param Notification $notification
     * @return MailHistory
     * @author k.yamamoto@balocco.info
     */
    public function toMailHistoryTable($notifiable, Notification $notification):MailHistory;
}