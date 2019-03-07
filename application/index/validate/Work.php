<?php

namespace app\index\validate;

use think\Validate;

class Work extends Validate
{

    protected $rule = [
        'doctor' => ['integer'],
        'start_time' => ['date'],
        'duration' => ['integer', 'between:0,5'],
        'product' => ['print', 'max:64', 'min:1'],
        'problem' => ['print', 'max:64', 'min:1'],
        'solution' => ['print', 'max:64', 'min:1'],
    ];

    protected $field = [
        'doctor' => '电医',
        'start_time' => '开始时间',
        'duration' => '持续时间',
        'product' => '产品及型号',
        'problem' => '问题',
        'solution' => '解决方案',
    ];

    public function sceneCreate()
    {
        foreach (array_keys($this->rule) as $key) {
            $this->append($key, 'require');
        }
    }
}
