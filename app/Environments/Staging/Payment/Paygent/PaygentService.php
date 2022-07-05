<?php


namespace App\Environments\Staging\Payment\Paygent;


use App\Environments\Interfaces\Paygent;
use App\Environments\Interfaces\Payment;
use App\Environments\Production\Payment\Paygent\PaygentService as ProductionPaygentService;

/**
 * ステージング用 paygent 関連処理
 * ステージングでの動作は、本番環境と同等の処理内容となるため、本番環境用のサービスクラスを継承
 * Class PaygentService
 * @package App\Environments\Staging\Payment
 * @author k.yamamoto@balocco.info
 */
class PaygentService extends ProductionPaygentService implements Paygent,Payment
{

}