<?php


namespace App\Common;


class SystemMessage extends BaseType
{
    const LEVEL_SUCCESS = "success";
    const LEVEL_WARNING = "warning";
    const LEVEL_DANGER = "danger";
    const LEVEL_INFO = "info";

    /**
     * @var string メッセージ内容
     */
    public $message;

    /**
     * @var string メッセージのレベル（success,info,warning,danger）
     */
    public $level;

    /**
     * @return array
     * @author k.yamamoto@balocco.info
     */
    public function getAttributes(): array
    {
        return ["message", "level"];
    }

}