<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Admin\BaseController;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends BaseController
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
    }

    public function showLoginForm()
    {
        return view('admin.auth.login'); //管理者ログインページのテンプレート
    }

    protected function guard()
    {
        return Auth::guard('admin'); //管理者認証のguardを指定
    }

    public function logout(Request $request)
    {
        //api用のトークンを削除する
        $request->user()->update(['api_token' => null]);

        Auth::guard('admin')->logout();
        $request->session()->flush();
        $request->session()->regenerate();


        return redirect('/admin/login');
    }

    /**
     * 認証後の処理。
     * API用のトークンを生成する処理を追加
     * @param Request $request
     * @param $admin
     * @author k.yamamoto@balocco.info
     */
    protected function authenticated(Request $request, $admin)
    {
        $admin->update(['api_token' => str_random(60)]);
    }

    public function username()
    {
        return 'account';
    }

    /**
     * 資格情報のカスタマイズ/ログイン処理時のadminsテーブル内検索条件を定義。
     * account 列がフォーム入力値と一致
     * password 列がフォーム入力値と一致
     * enabled 列がtrueと一致
     * @param Request $request
     * @return array
     * @author k.yamamoto@balocco.info
     */
    protected function credentials(Request $request)
    {
        $arrayCredentials = $request->only($this->username(), 'password');
        $arrayCredentials["enabled"] = true;
        return $arrayCredentials;
    }

}
