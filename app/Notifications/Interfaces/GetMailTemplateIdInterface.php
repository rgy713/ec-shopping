<?php


namespace App\Notifications\Interfaces;


interface GetMailTemplateIdInterface
{
    /**
     * 通知に対応するメールテンプレートIDを返す
     * @return int
     * @author k.yamamoto@balocco.info
     */
    static public function getMailTemplateId():int;
}