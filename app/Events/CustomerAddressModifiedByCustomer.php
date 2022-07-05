<?php


namespace App\Events;


use App\Events\Interfaces\AddressModified;
use App\Models\Customer;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * ユーザ本人による顧客住所変更
 * Class CustomerAddressModifiedByCustomer
 * @package App\Events
 * @author k.yamamoto@balocco.info
 */
class CustomerAddressModifiedByCustomer implements AddressModified
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * 変更前のデータを持つ配列
     * モデルの getOriginal() メソッドの返り値を期待
     *
     * @var array original
     */
    protected $original;

    /**
     * 変更後のデータを持つ配列
     * モデルの getAttributes() メソッドの返り値を期待
     * @var array
     */
    protected $attributes;


    public function getModified()
    {
        return $this->original;
    }

    public function getModifying()
    {
        return $this->attributes;
    }


    /**
     * CustomerAddressModifiedByCustomer constructor.
     * @param array $original 変更前データ
     * @param array $attributes 変更後データ
     */
    public function __construct(array $original, array $attributes)
    {
        $this->original = $original;
        $this->attributes = $attributes;
    }


}