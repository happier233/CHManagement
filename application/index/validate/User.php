<?php

namespace app\index\validate;

use think\Validate;

class User extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'    =>    ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'nick|用户名' => ['alphaDash', 'length' => '4,32'],
        'email|邮箱' => ['email'],
        'permission|权限' => ['in' => \app\index\model\User::permissions],
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'    =>    '错误信息'
     *
     * @var array
     */
    protected $message = [
        'permission.in' => '没有该权限',
        'password.require' => '请输入密码',
    ];

    public function sceneCreate() {
        $this->append('nick', 'require')
            ->append('password', 'require')
            ->append('email', 'require')
            ->append('permission', 'require');
    }
}
