<?php


namespace App\Environments\Testing\Payment\Paygent;


use App\Environments\Interfaces\Paygent;
use App\Environments\Develop\Payment\Paygent\PaygentStubService as DevelopStubService;
use App\Environments\Interfaces\Payment;

/**
 * テスト用 paygent 関連処理
 * テスト実行時はペイジェントサーバーへ決済処理のリクエストを行わないため、スタブとしての振る舞いを実装する
 * 実装内容は未定だが、暫定的に開発環境用のスタブを継承している。
 * Class PaygentService
 * @package App\Environments\Testing\Payment
 * @author k.yamamoto@balocco.info
 */
class PaygentStubService extends DevelopStubService implements Paygent,Payment
{

}