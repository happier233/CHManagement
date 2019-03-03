<?php
/**
 * Created by PhpStorm.
 * User: Happy233
 * Date: 2019/2/21
 * Time: 15:39
 */

namespace app\index\model;

use think\Model;

class Team extends Model
{
    protected $table = 'teams';
    protected $autoWriteTimestamp = 'datetime';

    public function doctors(){
        return $this->hasMany(Doctor::class, 'team');
    }

}