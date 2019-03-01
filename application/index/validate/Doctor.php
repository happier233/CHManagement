<?php

namespace app\index\validate;

use think\Validate;

class Doctor extends Validate
{

    protected $rule = [
        'name' => ['chs'],
        'id_code' => ['integer'],
        'stu_id' => ['integer'],
        'team' => ['integer'],
        'position' => ['integer', 'in' => \app\index\model\Doctor::positions],
    ];

    protected $message = [];

    protected function sceneCreate()
    {
        $keys = array_keys($this->rule);
        foreach ($keys as $key) {
            $this->append($key, 'require');
        }
    }
}
