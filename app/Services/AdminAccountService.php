<?php
/**
 * Created by PhpStorm.
 * User: rgy
 * Date: 4/2/2019
 * Time: 2:32 PM
 */

namespace App\Services;

use App\Models\AdminLoginLog;
use Illuminate\Support\Facades\DB;
use App\Models\Admin;
use Illuminate\Contracts\Auth\Factory as Auth;

class AdminAccountService
{
    /** @var Admin $model */
    protected $model;

    public function __construct(Auth $auth)
    {
        $this->model = $auth->user();
    }

    /**
     * @return mixed
     */
    public function getAdminList()
    {
        $last_login = AdminLoginLog::select('admin_id')
                        ->selectRaw('max(created_at) as last_login')
                        ->where('state', '=', DB::raw('TRUE'))
                        ->groupby('admin_id');

        $accounts = Admin::select('t0.id','t0.name','t0.account','t0.department','t0.enabled','t0.admin_role_id', 't1.last_login')
                    ->from(sprintf('%s as t0', Admin::make()->getTable()))
                    ->leftJoin(DB::raw(sprintf('(%s) as t1', $last_login->toSql())),'t0.id','t1.admin_id')
                    ->orderby('t0.id')
                    ->get();

        return $accounts;
    }

    /**
     * @param $id
     */
    public function changeDisable($id)
    {
        $account = Admin::find($id);
        $account->enabled = false;
        $account->save();
    }

    /**
     * @param $id
     */
    public function changeEnable($id)
    {
        $account = Admin::find($id);
        $account->enabled = true;
        $account->save();
    }

    /**
     * @param $params
     */
    public function editSave($params)
    {
        $account = Admin::find($params["id"]);
        $account->account = $params["account"];
        $account->password = bcrypt($params["password"]);
        $account->name = $params["name"];
        $account->department = $params["department"];
        $account->admin_role_id = $params["admin_role_id"];
        $account->enabled = $params["enabled"];
        $account->save();
    }

    /**
     * @param $params
     */
    public function createSave($params)
    {
        $account = new Admin();
        $account->account = $params["account"];
        $account->password = bcrypt($params["password"]);
        $account->name = $params["name"];
        $account->department = $params["department"];
        $account->admin_role_id = $params["admin_role_id"];
        $account->enabled = $params["enabled"];
        $account->save();
    }
}