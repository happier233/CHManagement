<?php

namespace app\index\validate;

use think\Validate;

class Work extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'    =>    ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'doctor' => ['integer'],
        'start_time' => ['date'],
        'duration' => ['integer', 'between:0,5'],
        'product' => ['print', 'max:64'],
        'problem' => ['print', 'max:64'],
        'solution' => ['print', 'max:64'],
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'    =>    '错误信息'
     *
     * @var array
     */
    protected $message = [];

    public function sceneCreate() {
        foreach (array_keys($this->rule) as $key) {
            $this->append($key, 'require');
        }
    }
}
