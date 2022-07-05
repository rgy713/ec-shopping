<?php


namespace App\Services;

use App\Models\MailHistory;

/**
 * メール関連共通処理
 * Class MailService
 * @package App\Services
 * @author k.yamamoto@balocco.info
 */
class MailService
{

    /**
     * 顧客ID、メールテンプレートIDを指定して、送信履歴が存在するかをチェックする
     * @param $customerId
     * @param $mailTemplateId
     * @return bool
     * @author k.yamamoto@balocco.info
     */
    public function customerMailHistoryExists($customerId,$mailTemplateId){
        $model = app(MailHistory::class);
        return $model
            ->where('customer_id','=',$customerId)
            ->where('mail_template_id','=',$mailTemplateId)
            ->exists();
    }

    /**
     * 受注ID、メールテンプレートIDを指定して、送信履歴が存在するかをチェックする
     * @param $orderId
     * @param $mailTemplateId
     * @return bool
     * @author k.yamamoto@balocco.info
     */
    public function orderMailHistoryExists($orderId,$mailTemplateId){
        $model = app(MailHistory::class);
        return $model
            ->where('order_id','=',$orderId)
            ->where('mail_template_id','=',$mailTemplateId)
            ->exists();
    }

}