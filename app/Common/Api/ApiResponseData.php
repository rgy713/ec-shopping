<?php


namespace App\Common\Api;


use App\Common\BaseType;
use Illuminate\Http\Request;

/**
 * Class ApiResponseData
 * API実行結果の形式を定義する。
 * @package App\Common\Api
 * @author k.yamamoto@balocco.info
 */
class ApiResponseData extends BaseType
{
    /** @var string $message */
    public $message;

    /**
     * toastrでのtoast出力時、及びvueコンポーネント側でのエラー制御に利用。
     * 原則、errorの場合は処理停止して原状回復処理を行い、それ以外の場合は処理を継続する。
     * @var string $status success,info,warning,error のいずれかを指定
     */
    public $status;

    /** @var array $modified データの作成、更新を行った場合、更新後のデータ */
    public $saved;

    /** @var array $requested リクエストされた情報 */
    public $requested;

    /**
     * ApiResponseData constructor.
     * @param array $requested
     */
    public function __construct(Request $request)
    {
        $this->requested = $request->toArray();
    }


    public function getAttributes(): array
    {
        // TODO: Implement getAttributes() method.
        return ["message", "status", "saved", "requested"];
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message)
    {
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status)
    {
        $this->status = $status;
    }

    /**
     * @return array
     */
    public function getSaved(): array
    {
        return $this->saved;
    }

    /**
     * @param array $saved
     */
    public function setSaved(array $saved)
    {
        $this->saved = $saved;
    }

    /**
     * @return array
     */
    public function getRequested(): array
    {
        return $this->requested;
    }

    /**
     * @param array $requested
     */
    public function setRequested(array $requested)
    {
        $this->requested = $requested;
    }


}