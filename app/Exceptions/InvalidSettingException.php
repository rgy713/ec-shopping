<?php


namespace App\Exceptions;

use Exception;
use Throwable;

/**
 * 設定に不備がある場合に発生させる例外クラス
 * Class InvalidSettingException
 * @package App\Exceptions
 * @author k.yamamoto@balocco.info
 */
class InvalidSettingException extends Exception
{

    /**
     * InvalidSettingException constructor.
     * @param string $settingFilePath 訂正すべき設定ファイルのパスを指定する。
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(
        string $settingFilePath,
        string $message = "",
        int $code = 0,
        Throwable $previous = null
    ) {
        $message = $message . " Correct the configuration file " . $settingFilePath;
        parent::__construct($message, $code, $previous);
    }

}