<?php


namespace App\Events\Interfaces;

/**
 * 住所変更
 * Interface AddressModified
 * @package App\Events\Interfaces
 */
interface AddressModified
{
    /**
     * @return array
     * @author k.yamamoto@balocco.info
     */
    public function getModified();

    /**
     * @return array
     * @author k.yamamoto@balocco.info
     */
    public function getModifying();
}