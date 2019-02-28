<?php
/**
 * Created by PhpStorm.
 * User: Happy233
 * Date: 2019/2/21
 * Time: 15:12
 */

namespace app\index\controller;

use app\common\Controller;
use app\index\model\User;
use think\facade\Session;
use think\helper\Hash;
use think\Request;
use think\Validate;

class Doctor extends Controller
{

    public function index()
    {
        return "Doctor Index";
    }

    public function login(Request $request)
    {
        $data = $request->post(['nick', 'password']);
        $nick = $data['nick'];
        $password = $data['password'];
        $captcha = $data['captcha'];
        $v = Validate::make([
            'nick|用户名' => ['require'],
            'password|密码' => ['require'],
            'captcha|验证码' => ['require', 'captcha']
        ]);
        if ($v->check($data)) {
            return $this->api(null, 1, $v->getError());
        }
        try {
            $user = User::where('nick', $nick)->find();
            if ($user == null) {
                return $this->api(null, 2, '用户或密码错误');
            }
            if (!Hash::check($password, $user->password)) {
                return $this->api(null, 2, '用户或密码错误');
            }
            if ($user->doctor == null) {
                return $this->api(null, 3, '非电医身份');
            }
            Session::set('login_id', $user->id);
            return $this->api(null, 0);
        } catch (\Exception $e) {
            return $this->api(null, 500, '系统内部错误', 500);
        }
    }

    public function emit(Request $request)
    {
        $data = $request->post(['start_time', 'ducation', 'product', 'problem', 'solution']);
        $this->validate($data, 'app\index\validate\Work');
    }
}