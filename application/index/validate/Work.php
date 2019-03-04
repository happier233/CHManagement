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
        'doctor|电医' => ['integer'],
        'start_time|开始时间' => ['date'],
        'duration|持续时间' => ['integer', 'between:0,5'],
        'product|产品及型号' => ['print', 'max:64', 'min:1'],
        'problem|问题' => ['print', 'max:64', 'min:1'],
        'solution|解决方案' => ['print', 'max:64', 'min:1'],
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
