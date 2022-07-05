<?php


namespace App\Services;

use App\Exceptions\InvalidDataStateException;
use App\Models\Masters\AdminRoleRouteSetting;

/**
 * 管理画面の権限系処理の実装を想定
 * Class AdminRoleService
 * @package App\Services
 * @author k.yamamoto@balocco.info
 */
class AdminRoleService
{

    /**
     * @var AdminRoleRouteSetting
     */
    protected $adminRoleRouteSetting;

    /**
     * AdminRoleService constructor.
     */
    public function __construct(AdminRoleRouteSetting $adminRoleRouteSetting)
    {
        $this->adminRoleRouteSetting=$adminRoleRouteSetting;
    }

    /**
     * $roleId が、$routeName の画面を表示する権限を有するかをboolで返す。
     * @param $routeName
     * @param $roleId
     * @return bool
     * @throws InvalidDataStateException
     * @author k.yamamoto@balocco.info
     */
    public function authorizeRouteWithRole($routeName,$roleId){
        $isAuthorized=false;

        //ルート名と権限IDに対応する画面表示可否設定を取得
        $resultCollection = $this->adminRoleRouteSetting
            ->where('route_name', "=",$routeName)
            ->where("admin_role_id", "=", $roleId)
            ->get();


        if ($resultCollection->count() === 0) {
            //ルート名とrole_idに対応するデータが存在しない データ投入漏れなので例外発生
            throw new InvalidDataStateException("admin_role_route_settings",
                "No data for route " . $routeName. " (with role_id:".$roleId.")");
        } elseif ($resultCollection->count() > 1) {
            //該当データが2件以上：データ投入作業に誤りがあるため、例外発生
            throw new InvalidDataStateException("admin_role_route_settings",
                "too many records for route " . $routeName . " (with role_id:".$roleId.")");
        }

        //ルート名/権限 に対応する、画面表示可否を返す。
        $isAuthorized=$resultCollection->first()->enabled;
        return $isAuthorized;
    }
}