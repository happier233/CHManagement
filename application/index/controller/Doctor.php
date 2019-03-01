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
use app\index\model\Work;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use think\facade\Session;
use think\facade\Url;
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
            return $this->api();
        } catch (\Exception $e) {
            return $this->api(null, 500, '系统内部错误', 500);
        }
    }

    public function emit(Request $request)
    {
        $data = $request->post(['start_time', 'duration', 'product', 'problem', 'solution']);
        $result = $this->validate($data, 'app\index\validate\Work');
        if ($request !== true) {
            return $this->api(null, 1, $result);
        }
        $doctor = $request->user->id;
        $data['doctor'] = $doctor;
        $work = new Work();
        $work->save($data);
        if ($request->has('qrcode')) {
            $qrcode = new QrCode(Url::build('index/Customer/emit', ['id' => $work->id]));
            $qrcode->setErrorCorrectionLevel(new ErrorCorrectionLevel(ErrorCorrectionLevel::HIGH));
            return $this->api(base64_encode($qrcode->writeString()));
        } else {
            return $this->api($work->id);
        }
    }
}