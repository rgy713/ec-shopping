<?php


namespace App\Common;

use Illuminate\Notifications\Notifiable;
use App\Common\SystemSetting;

/**
 * Class SystemAdministrator
 * システム管理者クラス
 * @package App\Common
 * @author k.yamamoto@balocco.info
 */
class SystemAdministrator
{
    use Notifiable;

    public $email;

    /**
     * SystemAdministrator constructor.
     */
    public function __construct()
    {
        /** @var SystemSetting $systemSetting */
        $systemSetting=app(SystemSetting::class);
        $this->email = $systemSetting->systemAdminMailAddress();
    }

}