<?php
/**
 * Created by PhpStorm.
 * User: Happy233
 * Date: 2019/2/21
 * Time: 15:39
 */

namespace app\index\model;

use think\Model;

class Work extends Model
{
    protected $table = 'works';
    protected $autoWriteTimestamp = 'datetime';

    public function doctor() {
        return $this->belongsTo(Doctor::class, 'doctor')->bind([
            'name'
        ]);
    }

    public function detail() {
        return $this->hasOne(WorkDetail::class);
    }

}