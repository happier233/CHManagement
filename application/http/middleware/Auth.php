<?php

namespace app\http\middleware;

use app\common\ApiResponse;
use app\index\model\User;
use think\facade\Session;
use think\Request;

class Auth
{
    use ApiResponse;

    public function handle(Request $request, \Closure $next)
    {
        if (!Session::has('login_id')) {
            return $this->api(null, 100, '请登录');
        }
        $user = User::get(Session::get('login_id'));
        if ($user == null) {
            Session::delete('login_id');
            return $this->api(null, 100, '请登录');
        }
        $request->user = $user;
        return $next($request);
    }
}
