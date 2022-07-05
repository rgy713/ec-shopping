<?php


namespace App\Events;


use App\Models\Customer;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * 顧客登録完了
 * Class CustomerRegistered
 * @package App\Events
 * @author k.yamamoto@balocco.info
 */
class CustomerRegistered
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var Customer
     */
    protected $registeredCustomer;

    /**
     * @return Customer
     */
    public function getRegisteredCustomer(): Customer
    {
        return $this->registeredCustomer;
    }



    /**
     * CustomerRegistered constructor.
     * @param Customer $registeredCustomer
     */
    public function __construct(Customer $registeredCustomer)
    {
        $this->registeredCustomer = $registeredCustomer;
    }
}