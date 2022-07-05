<?php


namespace App\Common;

use Illuminate\Notifications\Notifiable;
use App\Common\SystemSetting;

/**
 * Class OperationAdministrator
 * 運用管理者クラス
 * @package App\Common
 * @author k.yamamoto@balocco.info
 */
class OperationAdministrator
{
    use Notifiable;

    /**
     * OperationAdministrator constructor.
     */
    public function __construct()
    {
        /** @var SystemSetting $systemSetting */
        $systemSetting=app(SystemSetting::class);
        //運用管理者のメールアドレス
        $this->email = $systemSetting->operationAdminMailAddress();
    }

    /**
     * NotificationのUnitTest時にメソッドが必要なため、定義。
     * @return null
     * @author k.yamamoto@balocco.info
     */
    public function getKey(){
        return 1;
    }

}