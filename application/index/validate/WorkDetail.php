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
        'name|姓名' => ['chsAlpha', 'min:1', 'max:32'],
        'tel|手机号' => ['integer'],
        'stu_id|学号' => ['integer'],
        'college|学院' => ['chsAlpha'],
        'evaluation|评价' => ['integer', 'between:0,5'],
        'message|留言' => ['print', 'max:256'],
    ];

    public function sceneCreate()
    {
        foreach (array_keys($this->rule) as $key) {
            $this->append($key, 'require');
        }
    }

}