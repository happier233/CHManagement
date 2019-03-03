<?php
/**
 * 开心树上开心果, 开心树下你和我
 * User: happy233
 * Date: 2019-03-01
 * Time: 22:26
 */

namespace app\http\middleware;

use app\common\ApiResponse;
use app\index\model\User;
use think\facade\Session;
use think\Request;

class DoctorAuth
{

    use ApiResponse;

    public function handle(Request $request, \Closure $next) {
        if (!Session::has('doctor_login_id')) {
            return $this->api(null, 100, '请登录');
        }
        $user = User::get(Session::get('doctor_login_id'), ['doctor']);
        if ($user == null) {
            Session::delete('doctor_login_id');
            return $this->api(null, 100, '请登录');
        }
        if ($user->doctor == null) {
            return $this->api(null, 100, '非电医身份');
        }
        $request->user = $user;
        return $next($request);
    }

}