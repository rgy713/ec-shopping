<?php


namespace App\Services\Payment\Paygent;

use IteratorAggregate;
use ArrayAccess;

/**
 * Class PaygentResponse
 * ペイジェントへのリクエストの応答電文内容を格納するクラス。
 * 応答電文の内容は getData() メソッド経由で取得する。
 * 各応答電文のパラメータ名は、02_PG外部インターフェース仕様説明書.pdf の応答電文記載箇所を参照
 * @usage $object->getData()->get('payment_id');//応答電文パラメータpayment_idを取得する場合
 * @package App\Services\Payment\Paygent
 * @author k.yamamoto@balocco.info
 */
class PaygentResponse implements IteratorAggregate,ArrayAccess
{
    /**
     * 処理結果（result）の内容を保持。0:正常、1：異常
     * @var integer
     */
    protected $result;

    /**
     * レスポンスコード（response_code）の内容を保持。
     * resultが1の場合に設定される詳細エラーコード。
     * 詳細は「決済電文エラー一覧」および「カード決済エラーコード一覧」を参照してください。
     * @var string
     */
    protected $responseCode;

    /**
     * レスポンス詳細（response_detail）の内容を保持。
     *　resultが1の場合に設定される詳細エラー内容
     * レスポンスコードが異常の場合にセットされます。詳細は「カード決済エラーコード一覧」を参照してください。
     * @var string
     */
    protected $responseDetail;

    /**
     * 応答電文第4項目以降の内容を保持する、Collectionクラスを継承したオブジェクト。
     * @var PaygentResponseData
     */
    protected $data;

    /**
     * PaygentResponse constructor.
     * @param int $result
     * @param string $responseCode
     * @param string $responseDetail
     * @param PaygentResponseData $data
     */
    public function __construct(int $result, string $responseCode, string $responseDetail, PaygentResponseData $data)
    {
        $this->result = $result;
        $this->responseCode = $responseCode;
        $this->responseDetail = $responseDetail;
        $this->data = $data;
    }

    /**
     * 処理結果（result）の内容を返す。
     * 0:正常、1：異常
     * @return int
     */
    public function getResult(): int
    {
        return $this->result;
    }

    /**
     * レスポンスコード（response_code）の内容を返す。
     * 詳細は「決済電文エラー一覧」および「カード決済エラーコード一覧」を参照
     * @return string
     */
    public function getResponseCode(): string
    {
        return $this->responseCode;
    }

    /**
     * レスポンス詳細（response_detail）の内容を返す。
     * 詳細は「決済電文エラー一覧」および「カード決済エラーコード一覧」を参照
     * @return string
     */
    public function getResponseDetail(): string
    {
        return $this->responseDetail;
    }

    /**
     * 応答電文の第4項目以降の内容を保持するPaygentResponseDataクラスのオブジェクトを返す。
     * PaygentResponseDataを経由し、02_PG外部インターフェース仕様説明書.pdf 記載のパラメータ名でアクセス可能。
     * 応答結果が複数の場合は、二次元のコレクションとなる。
     * @usage $paygentResponse->getData()->get('payment_id');//電文020の結果から、4.決済IDを取得する場合
     * @usage $paygentResponse->getData()->get(0)->get('customer_card_id');//電文025の結果から1つ目の顧客カードIDを取得
     * @return PaygentResponseData
     */
    public function getData(): PaygentResponseData
    {
        return $this->data;
    }


    /**
     * @inheritDoc
     */
    public function getIterator()
    {
        return $this->data->getIterator();
    }

    /**
     * @inheritDoc
     */
    public function offsetExists($offset)
    {
        return $this->data->offsetExists($offset);
    }

    /**
     * @inheritDoc
     */
    public function offsetGet($offset)
    {
        return $this->data->offsetGet($offset);
    }

    /**
     * @inheritDoc
     */
    public function offsetSet($offset, $value)
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function offsetUnset($offset)
    {
        return null;
    }

}