<?php
/**
 * Created by PhpStorm.
 * User: rgy
 * Date: 4/22/2019
 * Time: 10:15 AM
 */

namespace App\Services;

use App\Common\SendMailImplementsGetOrder;
use App\Models\MailHistory;
use App\Models\MailTemplate;
use App\Models\Order;
use App\Notifications\OrderConfirmedNotification;
use App\Notifications\OrderShippedNotification;
use App\Notifications\PeriodicOrderShippedNotification;
use App\Notifications\ShippingScheduledNotification;

class MailHistoryService
{
    public function getMailHistory($orderId)
    {
        $model = app(MailHistory::class);
        $history_list = $model
            ->where('order_id', '=', $orderId)
            ->orderBy('created_at', 'DESC')
            ->get();
        return $history_list;
    }

    public function getMailTemplate($id)
    {
        $model = app(MailTemplate::class);
        $template = $model->find($id);
        $template_info = [
            "id" => $template->id,
            "name" => $template->name,
            "subject" => $template->subject,
            "body" => get_contents_html($template->body_file_path),
            "header" => get_contents_html($template->layout->header_file_path),
            "footer" => get_contents_html($template->layout->footer_file_path),
        ];
        return $template_info;
    }

    public function sendMail($params)
    {
        $order=app(Order::class)->find($params["id"]);
        //getOrder() メソッドを持つクラスのインスタンスを用意
        $getOrder=new SendMailImplementsGetOrder($order);

        if($params["mail_template_id"] == 1){
            //Notificationクラスのインスタンスを用意（下記は、メールテンプレート1番、ご注文内容のご確認(自動返信メール)　の送信例です）
            $notification=new OrderConfirmedNotification($getOrder, $params["subject"]);
            //受注の購入者にNotify（メール送信、及び履歴の保存が行われる）
            $order->customer->notify($notification);
        }
        elseif($params["mail_template_id"] == 4){
            $notification=new ShippingScheduledNotification($getOrder, $params["subject"]);
            $order->customer->notify($notification);
        }
        elseif($params["mail_template_id"] == 6){
            $notification=new OrderShippedNotification($getOrder, $params["subject"]);
            $order->customer->notify($notification);
        }
        elseif($params["mail_template_id"] == 12){
            $notification=new PeriodicOrderShippedNotification($getOrder, $params["subject"]);
            $order->customer->notify($notification);
        }
    }
}