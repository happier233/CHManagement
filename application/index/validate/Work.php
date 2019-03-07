<?php

namespace app\index\validate;

use think\Validate;

class Work extends Validate
{

    protected $rule = [
        'doctor|电医' => ['integer'],
        'start_time|开始时间' => ['date'],
        'duration|持续时间' => ['integer', 'between:0,5'],
        'product|产品及型号' => ['print', 'max:64', 'min:1'],
        'problem|问题' => ['print', 'max:64', 'min:1'],
        'solution|解决方案' => ['print', 'max:64', 'min:1'],
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
