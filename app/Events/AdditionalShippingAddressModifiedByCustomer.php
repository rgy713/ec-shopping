<?php


namespace App\Events;


use App\Events\Interfaces\AddressModified;
use App\Models\AdditionalShippingAddress;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * ユーザ本人による配送先住所変更
 * Class AdditionalShippingAddressModifiedByCustomer
 * @package App\Events
 * @author k.yamamoto@balocco.info
 */
class AdditionalShippingAddressModifiedByCustomer implements AddressModified
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * 変更前のデータを持つ配列
     * モデルの getOriginal() メソッドの返り値を期待
     *
     * @var array original
     */
    public $original;

    /**
     * 変更後のデータを持つ配列
     * モデルの getAttributes() メソッドの返り値を期待
     * @var array
     */
    public $attributes;

    public function getModified()
    {
        return $this->original;
    }

    public function getModifying()
    {
        return $this->attributes;
    }

    /**
     * AdditionalShippingAddressModifiedByCustomer constructor.
     * @param array $original
     * @param array $attributes
     */
    public function __construct(array $original, array $attributes)
    {
        $this->original = $original;
        $this->attributes = $attributes;
    }


}