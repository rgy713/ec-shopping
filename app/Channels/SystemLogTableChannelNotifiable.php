<?php


namespace App\Channels;


use App\Models\SystemLog;
use Illuminate\Notifications\Notification;

/**
 * システムログテーブルチャンネルを利用する通知クラスが実装するインターフェース
 * Interface SystemLogTableChannelNotifiable
 * @package App\Channels
 */
interface SystemLogTableChannelNotifiable
{
    public function toSystemLogTable($notifiable, Notification $notification): SystemLog;
}