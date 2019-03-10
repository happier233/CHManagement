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
        'name' => ['chsAlpha', 'min:1', 'max:32'],
        'tel' => ['integer'],
        'stu_id' => ['integer'],
        'college' => ['chsAlpha'],
        'evaluation' => ['integer', 'between:0,5'],
        'message' => ['print', 'max:256'],
    ];

    protected $field = [
        'name' => '姓名',
        'tel' => '手机号',
        'college' => '学院',
        'evaluation' => '评价',
        'message' => '留言',
    ];

    public function sceneCreate()
    {
        $keys = [
            'name',
            'tel',
            'college',
            'evaluation',
        ];
        foreach ($keys as $key) {
            $this->append($key, 'require');
        }
    }

}