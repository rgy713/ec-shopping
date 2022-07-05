<?php


namespace App\Environments\Develop\Payment\Paygent;


use App\Environments\Interfaces\Paygent;
use App\Environments\Interfaces\Payment;
use App\Models\Order;
use App\Models\PeriodicOrder;
use App\Services\Payment\Paygent\PaygentResponse;
use App\Services\Payment\Paygent\PaygentResponseData;

/**
 * 開発環境用 paygent 関連処理
 * 開発環境ではペイジェントサーバーとの通信が行えないため、スタブとしての振る舞いを実装する。
 * Class PaygentService
 * @package App\Environments\Develop\Payment
 * @author k.yamamoto@balocco.info
 */
class PaygentStubService implements Paygent, Payment
{
    /**
     * @inheritDoc
     */
    public function sendTelegramCreditAuth(Order $order, array $postParameters): PaygentResponse
    {
        $data = [
            "payment_id" => 999999999999999999,
            "trading_id" => $order->id,
            "issur_class" => 0,
            "acq_id" => "99663",
            "acq_name" => "abcdefghijklmnopqrstuvwxyzabcdefghijklmn",
            "issur_name" => "abcdefghijklmnopqrstuvwxyzabcdefghijklmn",
            "fc_auth_umu" => null,
            "daiko_code" => null,
            "card_shu_code" => null,
            "k_card_name" => null,
            "out_acs_html" => null,
            "issur_id" => 99999,
            "attempt_kbn" => null,
            "fingerprint" => "gdsgadsf",
            "masked_card_number" => '************9999',
            "card_valid_term" => 1225
        ];
        return new PaygentResponse(0, '', '', new PaygentResponseData($data));
    }

    /**
     * @inheritDoc
     */
    public function sendTelegramCreditAuthCancel($orderId = null, $settlementId = null): PaygentResponse
    {
        $data = [
            "payment_id" => $settlementId,
            "trading_id" => $orderId,
            "issur_class" => 0,
            "acq_id" => "99663",
            "acq_name" => "abcdefghijklmnopqrstuvwxyzabcdefghijklmn",
            "issur_name" => "abcdefghijklmnopqrstuvwxyzabcdefghijklmn",
            "fc_auth_umu" => null,
            "daiko_code" => null,
            "card_shu_code" => null,
            "k_card_name" => null,
            "out_acs_html" => null,
            "issur_id" => 99999,
            "attempt_kbn" => null,
            "fingerprint" => "gdsgadsf",
            "masked_card_number" => '************9999',
            "card_valid_term" => 1225
        ];
        return new PaygentResponse(0, '', '', new PaygentResponseData($data));
    }

    /**
     * @inheritDoc
     */
    public function sendTelegramCreditCommit($orderId = null, $settlementId = null): PaygentResponse
    {
        $data = [
            "payment_id" => $settlementId,
            "trading_id" => $orderId,
            "issur_class" => 0,
            "acq_id" => "99663",
            "acq_name" => "abcdefghijklmnopqrstuvwxyzabcdefghijklmn",
            "issur_name" => "abcdefghijklmnopqrstuvwxyzabcdefghijklmn",
            "fc_auth_umu" => null,
            "daiko_code" => null,
            "card_shu_code" => null,
            "k_card_name" => null,
            "out_acs_html" => null,
            "issur_id" => 99999,
            "attempt_kbn" => null,
            "fingerprint" => "gdsgadsf",
            "masked_card_number" => '************9999',
            "card_valid_term" => 1225
        ];
        return new PaygentResponse(0, '', '', new PaygentResponseData($data));
    }

    /**
     * @inheritDoc
     */
    public function sendTelegramCreditCommitCancel($orderId = null, $settlementId = null): PaygentResponse
    {
        $data = [
            "payment_id" => $settlementId,
            "trading_id" => $orderId,
            "issur_class" => 0,
            "acq_id" => "99663",
            "acq_name" => "abcdefghijklmnopqrstuvwxyzabcdefghijklmn",
            "issur_name" => "abcdefghijklmnopqrstuvwxyzabcdefghijklmn",
            "fc_auth_umu" => null,
            "daiko_code" => null,
            "card_shu_code" => null,
            "k_card_name" => null,
            "out_acs_html" => null,
            "issur_id" => 99999,
            "attempt_kbn" => null,
            "fingerprint" => "gdsgadsf",
            "masked_card_number" => '************9999',
            "card_valid_term" => 1225
        ];
        return new PaygentResponse(0, '', '', new PaygentResponseData($data));
    }

    /**
     * @inheritDoc
     */
    public function sendTelegramCreditStockStore($customerId, $cardToken, $otherParameters = null): PaygentResponse
    {
        $data = [
            "num_of_cards" => 1,
            "customer_card_id" => 9999999999999999,
            "issur_name" => "dasfsda",
            "fingerprint" => "",
            "masked_card_number" => "************9999",
            "card_valid_term" => "1225",
            "cardholder_name" => "TEST TEST"
        ];
        return new PaygentResponse(0, '', '', new PaygentResponseData($data));
    }

    /**
     * @inheritDoc
     */
    public function sendTelegramCreditStockDelete($customerId, $customerCardId = null): PaygentResponse
    {
        $data = [
            "num_of_cards" => 1
        ];
        return new PaygentResponse(0, '', '', new PaygentResponseData($data));
    }

    /**
     * @inheritDoc
     */
    public function sendTelegramCreditStockGet($customerId, $customerCardId = null): PaygentResponse
    {
        $data = [
            collect([
                'customer_id' => $customerId,
                'customer_card_id' => 9999999999999999,
                'card_number' => '************9999',
                'card_valid_term' => '1225',
                'card_last_use_date' => '201806011612',
                'card_brand' => '',
                'cardholder_name' => 'TEST NAME01',
                'add_info1' => '',
                'add_info2' => '',
                'add_info3' => '',
                'add_info4' => '',
                'card_expire_flag' => '',
                'valid_check_result' => '',
                'valid_update_cd' => '',
                'valid_check_date' => '',
                'issur_name' => '',
                'fingerprint' => ''
            ]),
            collect([
                'customer_id' => $customerId,
                'customer_card_id' => 9999999999999998,
                'card_number' => '************9998',
                'card_valid_term' => '1225',
                'card_last_use_date' => '201706011612',
                'card_brand' => '',
                'cardholder_name' => 'TEST NAME02',
                'add_info1' => '',
                'add_info2' => '',
                'add_info3' => '',
                'add_info4' => '',
                'card_expire_flag' => '',
                'valid_check_result' => '',
                'valid_update_cd' => '',
                'valid_check_date' => '',
                'issur_name' => '',
                'fingerprint' => ''

            ]),
            collect([
                'customer_id' => $customerId,
                'customer_card_id' => 9999999999999997,
                'card_number' => '************9997',
                'card_valid_term' => '1225',
                'card_last_use_date' => '201606011612',
                'card_brand' => '',
                'cardholder_name' => 'TEST NAME03',
                'add_info1' => '',
                'add_info2' => '',
                'add_info3' => '',
                'add_info4' => '',
                'card_expire_flag' => '',
                'valid_check_result' => '',
                'valid_update_cd' => '',
                'valid_check_date' => '',
                'issur_name' => '',
                'fingerprint' => ''
            ]),
        ];


        return new PaygentResponse(0, '', '', new PaygentResponseData($data));
    }

    /**
     * @inheritDoc
     */
    public function sendTelegramCreditAuthRevise(
        $orderId = null,
        $settlementId = null,
        int $paymentAmount
    ): PaygentResponse {
        $data = [
            "payment_id" => 999999999999999999,
            "trading_id" => $orderId,
            "base_payment_id" => $settlementId,
            "reduced_amount" => "",
            "issur_class" => 0,
            "acq_id" => "99663",
            "acq_name" => "abcdefghijklmnopqrstuvwxyzabcdefghijklmn",
            "issur_name" => "abcdefghijklmnopqrstuvwxyzabcdefghijklmn",
            "fc_auth_umu" => null,
            "daiko_code" => null,
            "card_shu_code" => null,
            "k_card_name" => null,
            "issur_id" => 99999,
            "attempt_kbn" => null
        ];
        return new PaygentResponse(0, '', '', new PaygentResponseData($data));
    }


    /**
     * @inheritDoc
     */
    public function sendTelegramCreditCommitRevise(
        $orderId = null,
        $settlementId = null,
        int $paymentAmount
    ): PaygentResponse {
        $data = [
            "payment_id" => 9999999999999999,
            "trading_id" => $orderId,
            "base_payment_id" => $settlementId,
            "reduced_amount" => "",
            "issur_class" => 0,
            "acq_id" => "99663",
            "acq_name" => "abcdefghijklmnopqrstuvwxyzabcdefghijklmn",
            "issur_name" => "abcdefghijklmnopqrstuvwxyzabcdefghijklmn",
            "fc_auth_umu" => null,
            "daiko_code" => null,
            "card_shu_code" => null,
            "k_card_name" => null,
            "issur_id" => 99999,
            "attempt_kbn" => null
        ];
        return new PaygentResponse(0, '', '', new PaygentResponseData($data));

    }

    /**
     * @inheritDoc
     */
    public function execPeriodicOrder(Order $order, PeriodicOrder $periodicOrder)
    {
        // TODO: Implement execPeriodicOrder() method.
    }
}
