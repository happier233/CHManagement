<?php
/**
 * Created by PhpStorm.
 * User: Happy233
 * Date: 2019/2/21
 * Time: 15:39
 */

namespace app\index\model;

use think\Model;

class Doctor extends Model
{
    protected $table = 'doctors';
    protected $autoWriteTimestamp = 'datetime';

    const DOCTOR = 0;
    const DEPUTY_TEAM_LEADER = 1;
    const TEAM_LEADER = 2;

    const positions = [
        self::DOCTOR,
        self::DEPUTY_TEAM_LEADER,
        self::TEAM_LEADER,
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id', 'id');
    }

}