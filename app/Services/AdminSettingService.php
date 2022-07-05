<?php


namespace App\Services;

use App\Models\Admin;
use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Contracts\Auth\Factory as Auth;

/**
 * Class AdminSettingService
 * API経由で使う場合、クラスのコンストラクタで依存注入すると$auth->user()がnullになるため注意
 * リクエストが来てからでないとtokenでの認証が出来ない、ということだと思われる。
 * API経由で使う場合、コントローラーのメソッドで依存注入すること。
 * @package App\Services
 * @author k.yamamoto@balocco.info
 */
class AdminSettingService
{

    /** @var Admin $model */
    protected $model;

    /**
     * AdminSettingService constructor.
     * @param Auth $auth
     */
    public function __construct(Auth $auth)
    {
        $this->model = $auth->user();
    }

    public function getSettings()
    {
        return [
            "option_left_menu" => $this->model->option_left_menu,
            "option_right_menu" => $this->model->option_right_menu
        ];
    }

    /**
     * @param $value
     * @return Admin
     * @author k.yamamoto@balocco.info
     */
    public function saveLeftMenuOption($value)
    {
        $this->model->option_left_menu = $value;
        $this->model->save();
        return $this->model;
    }

    /**
     * @param $value
     * @return Admin
     * @author k.yamamoto@balocco.info
     */
    public function saveRightMenuOption($value)
    {
        $this->model->option_right_menu = $value;
        $this->model->save();
        return $this->model;
    }

}