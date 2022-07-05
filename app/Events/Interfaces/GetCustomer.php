<?php


namespace App\Events\Interfaces;


use App\Models\Customer;

interface GetCustomer
{
    /**
     * @return Customer
     * @author k.yamamoto@balocco.info
     */
    public function getCustomer(): Customer;
}