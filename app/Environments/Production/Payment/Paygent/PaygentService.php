<?php


namespace App\Environments\Production\Payment\Paygent;

use App\Environments\Interfaces\Paygent;
use App\Environments\Interfaces\Payment;
use App\Models\Order;
use App\Models\PeriodicOrder;
use PaygentModule\System\PaygentB2BModule;
use App\Services\Payment\Paygent\PaygentResponse;

/**
 * 本番環境用 paygent 関連処理
 * TODO:実装
 * Class PaygentService
 * @package App\Environments\Production\Payment
 * @author k.yamamoto@balocco.info
 */
class PaygentService implements Paygent, Payment
{
    /**
     * @var PaygentB2BModule
     */
    protected $paygentModule;

    /**
     * PaygentService constructor.
     */
    public function __construct(PaygentB2BModule $paygentModule)
    {
        $this->paygentModule = $paygentModule;
    }

    /**
     * @inheritDoc
     */
    public function sendTelegramCreditAuth(Order $order, array $postParameters): PaygentResponse
    {
        // TODO: Implement sendTelegramCreditAuth() method.
    }

    /**
     * @inheritDoc
     */
    public function sendTelegramCreditAuthCancel($orderId = null, $settlementId = null): PaygentResponse
    {
        // TODO: Implement sendTelegramCreditAuthCancel() method.
    }

    /**
     * @inheritDoc
     */
    public function sendTelegramCreditCommit($orderId = null, $settlementId = null): PaygentResponse
    {
        // TODO: Implement sendTelegramCreditCommit() method.
    }

    /**
     * @inheritDoc
     */
    public function sendTelegramCreditCommitCancel($orderId = null, $settlementId = null): PaygentResponse
    {
        // TODO: Implement sendTelegramCreditCommitCancel() method.
    }

    /**
     * @inheritDoc
     */
    public function sendTelegramCreditStockStore($customerId, $cardToken, $otherParameters = null): PaygentResponse
    {
        // TODO: Implement sendTelegramCreditStockStore() method.
    }

    /**
     * @inheritDoc
     */
    public function sendTelegramCreditStockDelete($customerId, $customerCardId = null): PaygentResponse
    {
        // TODO: Implement sendTelegramCreditStockDelete() method.
    }

    /**
     * @inheritDoc
     */
    public function sendTelegramCreditStockGet($customerId, $customerCardId = null): PaygentResponse
    {
        // TODO: Implement sendTelegramCreditStockGet() method.
    }

    /**
     * @inheritDoc
     */
    public function sendTelegramCreditAuthRevise($paymentAmount): PaygentResponse
    {
        // TODO: Implement sendTelegramCreditAuthRevise() method.
    }

    /**
     * @inheritDoc
     */
    public function sendTelegramCreditCommitRevise($paymentAmount): PaygentResponse
    {
        // TODO: Implement sendTelegramCreditCommitRevise() method.
    }

    /**
     * @inheritDoc
     */
    public function execPeriodicOrder(Order $order, PeriodicOrder $periodicOrder): PaygentResponse
    {
        // TODO: Implement execPeriodicOrder() method.
    }


}