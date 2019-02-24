<?php

namespace app\index\validate;

use think\Validate;

class Doctor extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'    =>    ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'id' => ['require', 'integer'],
        'name' => ['require', 'chs'],
        'id_code' => ['require', 'integer'],
        'stu_id' => ['require', 'integer'],
        'team' => ['require', 'integer'],
        'position' => ['require', 'integer', 'in' => \app\index\model\Doctor::positions],
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'    =>    '错误信息'
     *
     * @var array
     */
    protected $message = [];
}
