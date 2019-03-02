<?php
/**
 * Created by PhpStorm.
 * User: Happy233
 * Date: 2019/3/2
 * Time: 20:02
 */

namespace app\index\validate;

use think\Validate;

class WorkDetail extends Validate
{

    protected $rule = [
        'name' => ['chsAlpha'],
        'tel' => ['integer'],
        'stu_id' => ['integer'],
        'college' => ['chsAlpha'],
        'evaluation' => ['integer', 'between:0,5'],
        'message' => ['print', 'max:256'],
    ];

    public function sceneCreate()
    {
        foreach (array_keys($this->rule) as $key) {
            $this->append($key, 'require');
        }
    }

}