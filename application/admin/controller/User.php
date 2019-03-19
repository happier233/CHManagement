<?php

namespace app\admin\controller;

use app\common\ApiResponse;
use think\facade\Config;
use think\facade\Session;
use think\facade\Validate;
use think\helper\Hash;
use think\Model\Collection;
use think\Controller;
use think\Request;
use app\index\validate\User as UserValidator;
use app\index\model\User as UserModel;

class User extends Controller
{

    use ApiResponse;

    protected $middleware = [
        'Auth' => ['except' => ['login']],
        'ViewUser' => ['only' => ['list', 'read', 'create', 'update', 'delete']],
        'EditUser' => ['only' => ['create', 'update', 'delete']],
    ];

    public function login(Request $request) {
        $data = $request->only(['nick', 'password', 'captcha'], 'post');
        $v = Validate::make([
            'nick|用户名' => ['require'],
            'password|密码' => ['require'],
            'captcha|验证码' => Config::get('app_debug') ? ['captcha'] : ['require', 'captcha'],
        ]);
        if (!$v->check($data)) {
            return $this->api(null, 1, $v->getError());
        }
        $nick = $data['nick'];
        $password = $data['password'];
        try {
            $user = UserModel::where('nick', $nick)->find();
            if ($user == null) {
                return $this->api(null, 2, '用户或密码错误');
            }
            if (!Hash::check($password, $user->password)) {
                return $this->api(null, 2, '用户或密码错误');
            }
            Session::set('login_id', $user->id);
            return $this->api($user->visible(['id', 'nick']));
        } catch (\Exception $e) {
            return $this->api(null, 500, '系统内部错误', 500);
        }
    }

    public function check(Request $request) {
        /** @var UserModel $user */
        $user = $request->user;
        if ($user->doctor) {
            /** @var \app\index\model\Doctor $doctor */
            $doctor = $user->doctor;
            $doctor->visible(['name']);
        }
        return $this->api($user->visible(['id', 'nick', 'doctor.name']));
    }

    /**
     * 显示用户列表
     *
     * @param Request $request
     * @param int $page
     * @param int $count
     * @return \think\Response
     * @throws \Exception
     */
    public function list(Request $request, $page = 1, $count = 20) {
        $keys = ['id', 'nick', 'email', 'permission'];
        $data = filterEmpty($request->only($keys, 'post'));
        $keys = array_keys($data);
        // counts
        $counts = (new UserModel())->withSearch($keys, $data)->count('id');
        // users
        $user = (new UserModel())->page($page, $count)->append(['is_doctor'])->withJoin(['doctor'], 'LEFT');
        $user = $user->withSearch($keys, $data)->select();
        /** @var Collection $user */
        $user->visible(['id', 'nick', 'email', 'is_doctor', 'create_time', 'update_time']);
        return $this->api([
            'counts' => $counts,
            'pages' => max(1, ceil($counts / $count)),
            'list' => $user,
        ]);
    }

    /**
     * 显示创建资源表单页.
     *
     * @param  \think\Request $request
     * @return \think\Response
     */
    public function create(Request $request) {
        $data = $request->post([
            'nick',
            'email',
            'password',
            'permission',
        ]);
        $result = $this->validate($data, 'app\index\validate\User.create');
        if ($request !== true) {
            return $this->api(null, 1, $result);
        }
        $user = new UserModel();
        if ($user->where(['nick', $data['nick']])->whereOr('email', $data['email'])->count('id') > 0) {
            return $this->api(null, 1, '该用户名或邮箱已经存在');
        }
        $user = (new UserModel())->save($data);
        return $this->api($user);
    }

    /**
     * 显示指定的资源
     *
     * @param  int $id
     * @return \think\Response
     */
    public function read($id) {
        $user = UserModel::get($id);
        if ($user == null) {
            return $this->api(null, 1, "用户不存在");
        }
        $user->visible(['id', 'nick', 'email', 'create_time', 'update_time']);
        return $this->api($user, 0);
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param Request $request
     * @param  int $id
     * @return \think\Response
     */
    public function update(Request $request, $id) {
        $data = $request->post([
            'nick',
            'email',
            'password',
            'permission',
        ]);
        $result = $this->validate($data, UserValidator::class);
        if ($result !== true) {
            return $this->api(null, 1, $result);
        }
        /** @var UserModel $user */
        $user = UserModel::get($id);
        if ($user == null) {
            return $this->api(null, 1, "用户不存在");
        }
        $user->save($data);
        return $this->api(null, 0);
    }

    /**
     * 删除指定资源
     *
     * @param  int $id
     * @return \think\Response
     */
    public function delete($id) {
        /** @var UserModel $user */
        $user = UserModel::get($id);
        if ($user == null) {
            return $this->api(null, 1, "用户不存在");
        }
        try {
            $user->delete();
            return $this->api(null, 0);
        } catch (\Exception $e) {
            return $this->api(null, 1, '系统内部错误');
        }
    }
}
