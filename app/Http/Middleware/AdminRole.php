<?php

namespace App\Http\Middleware;

use App\Exceptions\InvalidDataStateException;
use App\Services\AdminRoleService;
use Closure;

/**
 * 管理画面ログイン者が画面を表示する権限を有するかを判定するミドルウェア
 * Class AdminRole
 * @package App\Http\Middleware
 * @author k.yamamoto@balocco.info
 */
class AdminRole
{
    /**
     * @var AdminRoleService
     */
    protected $adminRoleService;

    /**
     * Hoge constructor.
     */
    public function __construct(AdminRoleService $adminRoleService)
    {
        $this->adminRoleService=$adminRoleService;
    }

    /**
     * @param $request
     * @param Closure $next
     * @return mixed
     * @throws InvalidDataStateException
     * @author k.yamamoto@balocco.info
     */
    public function handle($request, Closure $next)
    {
        if (!$this->adminRoleService->authorizeRouteWithRole($request->route()->getName(),$request->user()->admin_role_id)) {
            abort(403, "");
        }
        return $next($request);
    }
}