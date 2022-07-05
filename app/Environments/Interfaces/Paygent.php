<?php


namespace App\Environments\Interfaces;

use App\Models\Order;
use App\Services\Payment\Paygent\PaygentResponse;

/**
 * ペイジェント
 * Interface Paygent
 * @package App\Environments\Interfaces
 */
interface Paygent
{
    /**
     * 共通処理 #1010
     * 020.カード決済オーソリ電文を送信し、結果を返却する
     * @param Order $order
     * @param array $postParameters
     * @return PaygentResponse
     * @author k.yamamoto@balocco.info
     *
     */
    public function sendTelegramCreditAuth(Order $order, array $postParameters): PaygentResponse;

    /**
     * 共通処理 #1020
     * 021.カード決済オーソリキャンセル電文を送信し、結果を返却する
     * 引数$orderId、$settlementIdのいずれかを指定。
     * @param null $orderId 受注ID。ペイジェントI/F仕様書におけるtrading_id
     * @param null $settlementId 決済ベンダ側ID。ペイジェントI/F仕様書におけるpayment_id
     * @return PaygentResponse
     * @author k.yamamoto@balocco.info
     */
    public function sendTelegramCreditAuthCancel($orderId = null, $settlementId = null): PaygentResponse;

    /**
     * 共通処理 #1030
     * 022.カード決済売上電文を送信し、結果を返却する
     * 引数$orderId、$settlementIdのいずれかを指定。
     * @param null $orderId 受注ID。ペイジェントI/F仕様書におけるtrading_id
     * @param null $settlementId 決済ベンダ側ID。ペイジェントI/F仕様書におけるpayment_id
     * @return PaygentResponse
     * @author k.yamamoto@balocco.info
     */
    public function sendTelegramCreditCommit($orderId = null, $settlementId = null): PaygentResponse;

    /**
     * 共通処理 #1040
     * 023.カード決済売上キャンセル電文を送信し、結果を返却する
     * 引数$orderId、$settlementIdのいずれかを指定。
     * @param null $orderId 受注ID。ペイジェントI/F仕様書におけるtrading_id
     * @param null $settlementId 決済ベンダ側ID。ペイジェントI/F仕様書におけるpayment_id
     * @return PaygentResponse
     * @author k.yamamoto@balocco.info
     */
    public function sendTelegramCreditCommitCancel($orderId = null, $settlementId = null): PaygentResponse;

    /**
     * 共通処理 #1050
     * 025.カード情報設定電文を送信し、結果を返却する
     * @param $customerId カードを所有する顧客のID。ペイジェントI/F仕様書におけるcustomer_id
     * @param $cardToken カード情報トークン。ペイジェントI/F仕様書におけるcard_token
     * @param null $otherParameters customer_id、card_token以外のパラメータを指定する場合のオプション配列
     * @return PaygentResponse
     * @author k.yamamoto@balocco.info
     */
    public function sendTelegramCreditStockStore($customerId, $cardToken, $otherParameters = null): PaygentResponse;


    /**
     * 共通処理 #1060
     * 026.カード情報削除電文を送信し、結果を返却する
     * @param $customerId カードを所有する顧客のID。ペイジェントI/F仕様書におけるcustomer_id
     * @param null $customerCardId カード識別子　ペイジェントI/F仕様書におけるcustomer_card_id
     * @return PaygentResponse
     * @author k.yamamoto@balocco.info
     */
    public function sendTelegramCreditStockDelete($customerId, $customerCardId = null): PaygentResponse;

    /**
     * 027.カード情報照会電文を送信し、結果を返却する。
     * PaygentResponseの持つ結果情報は、複数のカード情報を持つ2次元のCollectionとなる。
     * @param $customerId カードを所有する顧客のID。ペイジェントI/F仕様書におけるcustomer_id
     * @param null $customerCardId カード識別子　ペイジェントI/F仕様書におけるcustomer_card_id
     * @return PaygentResponse
     * @author k.yamamoto@balocco.info
     */
    public function sendTelegramCreditStockGet($customerId, $customerCardId = null): PaygentResponse;

    /**
     * 028.カード決済補正オーソリ電文を reduction_flag=0 で送信し、結果を返却する
     * @param null $orderId 受注ID。ペイジェントI/F仕様書におけるtrading_id
     * @param null $settlementId 決済ベンダ側ID。ペイジェントI/F仕様書におけるpayment_id
     * @param int $paymentAmount 補正後の決済金額。ペイジェントI/F仕様書におけるpayment_amount
     * @return PaygentResponse
     * @author k.yamamoto@balocco.info
     */
    public function sendTelegramCreditAuthRevise(
        $orderId = null,
        $settlementId = null,
        int $paymentAmount
    ): PaygentResponse;

    /**
     * 029.カード決済補正売上電文を送信し、reduction_flag=0 で送信し、結果を返却する
     * @param null $orderId 受注ID。ペイジェントI/F仕様書におけるtrading_id
     * @param null $settlementId 決済ベンダ側ID。ペイジェントI/F仕様書におけるpayment_id
     * @param int $paymentAmount 補正後の決済金額。ペイジェントI/F仕様書におけるpayment_amount
     * @return PaygentResponse
     * @author k.yamamoto@balocco.info
     */
    public function sendTelegramCreditCommitRevise(
        $orderId = null,
        $settlementId = null,
        int $paymentAmount
    ): PaygentResponse;


}