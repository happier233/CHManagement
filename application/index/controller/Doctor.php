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

    protected $middleware = [
        'DoctorAuth' => ['only' => ['emit', 'check']],
    ];

    public function index() {
        return "Doctor Index";
    }

    public function login(Request $request) {
        $data = $request->only(['nick', 'password', 'captcha'], 'post');
        $v = Validate::make([
            'nick|用户名' => ['require'],
            'password|密码' => ['require'],
            'captcha|验证码' => ['require', 'captcha']
        ]);
        if (!$v->check($data)) {
            return $this->api(null, 1, $v->getError());
        }
        $nick = $data['nick'];
        $password = $data['password'];
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
            Session::set('doctor_login_id', $user->id);
            return $this->api($user->id);
        } catch (\Exception $e) {
            return $this->api(null, 500, '系统内部错误', 500);
        }
    }

    public function emit(Request $request) {
        $data = $request->only(['start_time', 'duration', 'product', 'problem', 'solution'], 'post');
        $result = $this->validate($data, 'app\index\validate\Work');
        if ($request !== true) {
            return $this->api(null, 1, $result);
        }
        $doctor = $request->user->id;
        $data['doctor'] = $doctor;
        $work = new Work();
        $work->save($data);
        if ($request->has('qrcode')) {
            $qrcode = new QrCode(Url::build('index/Customer/info', ['id' => $work->id]));
            $qrcode->setErrorCorrectionLevel(new ErrorCorrectionLevel(ErrorCorrectionLevel::HIGH));
            return $this->api(base64_encode($qrcode->writeString()));
        } else {
            return $this->api($work->id);
        }
    }

    public function check(Request $request) {
        return $this->api([
            "id" => $request->user->id,
            "nick" => $request->user->nick,
            "name" => $request->doctor->name,
        ]);
    }
}