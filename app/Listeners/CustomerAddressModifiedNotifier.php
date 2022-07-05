<?php


namespace App\Listeners;

use App\Common\OperationAdministrator;
use App\Events\CustomerAddressModifiedByCustomer;
use App\Events\Interfaces\AddressModified;
use App\Notifications\CustomerAddressModifiedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * 顧客自身の操作による住所情報の変更を運用管理者に通知する
 * Class CustomerAddressModifiedByCustomerNotifier
 * @package App\Listeners
 * @author k.yamamoto@balocco.info
 */
class CustomerAddressModifiedNotifier implements ShouldQueue
{
    use FailedWithQueue;


    /**
     * CustomerAddressModifiedByCustomerNotifier constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param $event
     * @author k.yamamoto@balocco.info
     */
    public function handle(AddressModified $event)
    {
        //住所関連列に変更があるかをチェック
        if ($this->checkAddressModified($event)) {
            //住所関連列に変更があるので、運用管理者への通知を行う
            (new OperationAdministrator())->notify(new CustomerAddressModifiedNotification($event));
        }
    }

    /**
     * 住所関連列が変更されているかをチェックし、変更されている場合true、変更されていない場合falseを返す
     * @param AddressModified $event
     * @return bool
     * @author k.yamamoto@balocco.info
     */
    protected function checkAddressModified(AddressModified $event)
    {
        $modified = $event->getModified();
        $modifying = $event->getModifying();

        foreach (['prefecture_id', 'address01', 'address02', 'zipcode'] as $checkTargetKey) {
            if($modified[$checkTargetKey] !== $modifying[$checkTargetKey]){
                return true;
            }
        }
        //住所関連列変更なし
        return false;
    }

}