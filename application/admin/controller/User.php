<?php

namespace app\admin\controller;

use app\common\ApiResponse;
use think\Model\Collection;
use think\Controller;
use think\Request;
use app\index\validate\User as UserValidator;
use app\index\model\User as UserModel;

class User extends Controller
{

    use ApiResponse;

    protected $middleware = [
        'Auth',
        'ViewUser' => ['only' => ['list', 'read', 'create', 'update', 'delete']],
        'EditUser' => ['only' => ['create', 'update', 'delete']],
    ];

    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //
    }

    /**
     * 显示用户列表
     *
     * @return \think\Response
     */
    public function list(Request $request)
    {
        $page = $request->get('page', 1);
        $count = $request->get('count', 20);
        /** @var Collection $user */
        $user = (new UserModel())->page($page, $count)->fetchCollection()->select();
        $user->visible(['id', 'nick', 'email', 'create_time', 'update_time']);
        return $this->api($user, 0);
    }

    /**
     * 显示创建资源表单页.
     *
     * @param  \think\Request $request
     * @return \think\Response
     */
    public function create(Request $request)
    {
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
        return $this->api(null, 0, $result);
    }

    /**
     * 显示指定的资源
     *
     * @param  int $id
     * @return \think\Response
     */
    public function read($id)
    {
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
    public function update(Request $request, $id)
    {
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
    public function delete($id)
    {
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
