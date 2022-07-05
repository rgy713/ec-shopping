<?php


namespace App\Events;


use App\Events\Interfaces\AddressModified;
use App\Models\AdditionalShippingAddress;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * ユーザ本人による配送先住所追加
 * Class AdditionalShippingAddressRegisteredByCustomer
 * @package App\Events
 * @author k.yamamoto@balocco.info
 */
class AdditionalShippingAddressRegisteredByCustomer implements AddressModified
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var AdditionalShippingAddress
     */
    public $address;

    public function getModified()
    {
        return $this->address;
    }

    public function getModifying()
    {
        return [];
    }

    /**
     * AdditionalShippingAddressRegistered constructor.
     * @param AdditionalShippingAddress $address
     */
    public function __construct(AdditionalShippingAddress $address)
    {
        $this->address = $address;
    }


}