<?php

namespace App\Listeners;

use App\Common\OperationAdministrator;
use App\Events\PeriodicInfoModifiedByCustomer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\PeriodicInfoModifiedNotification;

class PeriodicInfoModifiedNotifier implements ShouldQueue
{
    use FailedWithQueue;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     * @param  PeriodicInfoModifiedByCustomer $event
     * @return void
     */
    public function handle(PeriodicInfoModifiedByCustomer $event)
    {
        //定期間隔、ご依頼主住所情報、お届け先住所情報が変更されている場合に運用管理者に通知を行う
        $condition = $this->checkModified($event);

        if ($condition) {
            (new OperationAdministrator())->notify(new PeriodicInfoModifiedNotification($event));
        }

    }

    /**
     * 通知を行うかを判定する。
     * 購入者住所
     * 定期間隔
     * 配送先住所
     * のいずれかが変更されている場合はtrueを返す。
     * @param PeriodicInfoModifiedByCustomer $event
     * @return bool
     * @author k.yamamoto@balocco.info
     */
    protected function checkModified(PeriodicInfoModifiedByCustomer $event)
    {
        if($this->checkPeriodicAddressModified($event)){
            return true;
        }
        if($this->checkIntervalModified($event)){
            return true;
        }
        if($this->checkShippingAddressModified($event)) {
            return true;
        }
        return false;
    }

    /**
     * 購入者住所が変更されている場合trueを返す
     * @param PeriodicInfoModifiedByCustomer $event
     * @return bool
     * @author k.yamamoto@balocco.info
     */
    protected function checkPeriodicAddressModified(PeriodicInfoModifiedByCustomer $event){
        //購入者住所情報が変更されているか検査
        /** @var $periodicOrderDiff 定期情報の変更前/変更後の差分 */
        $periodicOrderDiff = array_diff($event->getPeriodicOrderOriginal(),$event->getPeriodicOrderAttributes());

        //全体の差分がないので、変更されていない。
        if(count($periodicOrderDiff) ===0){
            //変更されていないので、false
            return false;
        }
        foreach (['prefecture_id','address01','address02','zipcode'] as $checkTargetKey){
            //変更差分に住所関連列が含まれているかをチェック
            if(array_key_exists($checkTargetKey,$periodicOrderDiff)){
                //住所関連列に1点でも変更がある場合、通知する
                return true;
            }
        }

        //住所関連列変更なし
        return false;
    }

    /**
     * 定期間隔が変更されている場合trueを返す
     * @param PeriodicInfoModifiedByCustomer $event
     * @return bool
     * @author k.yamamoto@balocco.info
     */
    protected function checkIntervalModified(PeriodicInfoModifiedByCustomer $event){
        //購入者住所情報が変更されているか検査
        /** @var $periodicOrderDiff 定期情報の変更前/変更後の差分 */
        $periodicOrderDiff = array_diff($event->getPeriodicOrderOriginal(),$event->getPeriodicOrderAttributes());

        //全体の差分がないので、変更されていない。
        if(count($periodicOrderDiff) ===0){
            //変更されていないので、false
            return false;
        }

        foreach (['periodic_interval_type_id','interval_days','interval_month','interval_date_of_month'] as $checkTargetKey){
            //変更差分に住所関連列が含まれているかをチェック
            if(array_key_exists($checkTargetKey,$periodicOrderDiff)){
                //住所関連列に1点でも変更がある場合、通知する
                return true;
            }
        }

        //住所関連列変更なし
        return false;

    }

    /**
     * 配送先住所が変更されている場合trueを返す
     * @param PeriodicInfoModifiedByCustomer $event
     * @return bool
     * @author k.yamamoto@balocco.info
     */
    protected function checkShippingAddressModified(PeriodicInfoModifiedByCustomer $event){
        //配送先住所情報が変更されているか検査
        /** @var $periodicShippingDiff 定期情報の変更前/変更後の差分 */
        $periodicShippingDiff = array_diff($event->getPeriodicShippingOriginal(),$event->getPeriodicShippingAttributes());

        //全体の差分がないので、変更されていない。
        if(count($periodicShippingDiff) ===0){
            //変更されていないので、false
            return false;
        }
        foreach (['prefecture_id','address01','address02','zipcode'] as $checkTargetKey){
            //変更差分に住所関連列が含まれているかをチェック
            if(array_key_exists($checkTargetKey,$periodicShippingDiff)){
                //住所関連列に1点でも変更がある場合、通知する
                return true;
            }
        }
        //住所関連列変更なし
        return false;
    }
}
