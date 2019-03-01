<?php
/**
 * Created by PhpStorm.
 * User: happy233
 * Date: 2019-03-01
 * Time: 11:39
 */

namespace app\index\model;

use think\Model;

class WorkDetail extends Model
{

    protected $table = 'work_detail';

    protected $autoWriteTimestamp = 'datetime';
    protected $createTime = 'confirm_time';
    protected $updateTime = false;

    public function work() {
        return $this->belongsTo(Work::class);
    }

}