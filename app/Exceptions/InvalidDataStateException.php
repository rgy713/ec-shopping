<?php


namespace App\Exceptions;

use Exception;
use Throwable;

/**
 * データベース内容に不備がある場合に発生させる例外クラス
 * Class InvalidSettingException
 * @package App\Exceptions
 * @author k.yamamoto@balocco.info
 */
class InvalidDataStateException extends Exception
{

    /**
     * InvalidDataStateException constructor.
     * @param string $tableName
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $tableName, string $message = "", int $code = 0, Throwable $previous = null)
    {
        $message = $message . " Correct the data in the table  " . $tableName.".";
        parent::__construct($message, $code, $previous);
    }

}