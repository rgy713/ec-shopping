<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Customer extends Model
{
    use SoftDeletes;

    protected $hidden = ['password'];

    protected $dates = ['deleted_at'];
    //mail通知先の決定ロジックをオーバーライドするので、traitに別名をあてている
//    use Notifiable{
//        Notifiable::routeNotificationFor as traitRouteNotificationFor;
//    }
    use Notifiable;

    /**
     * mail通知先の決定ロジック
     * @return bool|mixed|null|string
     * @author k.yamamoto@balocco.info
     */
    public function routeNotificationForMail()
    {
        /**
         * 旧システムではemail列が必須のため、運用時に存在しないメールアドレス ing@fleuri.cc 等を入力して保存している場合がある。
         * ing@fleuri.cc は存在しないメールアドレスのため、以下判定してfalseを返す。
         */
        if (strpos($this->email, "ing@fleuri.cc") === false) {
            // ing@fleuri.cc が含まれていないので送信してOK
            return $this->email;
        } else {
            // ing@fleuri.cc が含まれている → ダミーアドレスのため、false
            return false;
        }
    }

//    /**
//     * @param $driver
//     * @return bool|mixed|null|string
//     * @author k.yamamoto@balocco.info
//     */
//    public function routeNotificationFor($driver)
//    {
//        switch ($driver) {
//            case 'mail':
//                /**
//                 * 旧システムではemail列が必須のため、運用時に存在しないメールアドレス ing@fleuri.cc 等を入力して保存している場合がある。
//                 * ing@fleuri.cc は存在しないメールアドレスのため、以下判定してfalseを返す。
//                 */
//                if(strpos($this->email,"ing@fleuri.cc") === false){
//                    // ing@fleuri.cc が含まれていないので送信してOK
//                    return $this->email;
//                }else{
//                    // ing@fleuri.cc が含まれている → ダミーアドレスのため、false
//                    return false;
//                }
//
//            default :
//                return $this->traitRouteNotificationFor($driver);
//        }
//    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class,'customer_id','id');
    }

    public function periodicOrders()
    {
        return $this->hasMany(PeriodicOrder::class,'customer_id','id');
    }

    public function attachments()
    {
        return $this->hasMany(CustomerAttachments::class,'customer_id','id');
    }

    public function shopMemos()
    {
        return $this->hasMany(ShopMemo::class,'customer_id','id');
    }
}
