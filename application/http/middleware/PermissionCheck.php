<?php
/**
 * Created by PhpStorm.
 * User: Happy233
 * Date: 2019/2/21
 * Time: 19:42
 */

namespace app\http\middleware;

use app\common\ApiResponse;
use app\index\model\User;
use think\Request;

abstract class PermissionCheck
{
    use ApiResponse;

    public function handle(Request $request, \Closure $next)
    {
        if (!isset($request->user) || !($request->user instanceof User)) {
            return $this->api(null, 200, '系统内部错误');
        }
        /** @var User $user */
        $user = $request->user;
        if (!$this->check($user)) {
            return $this->api(null, 2, '权限不足');
        }

        return $next($request);
    }

    public abstract function check(User $user): bool;

}