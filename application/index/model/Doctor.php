<?php
/**
 * Created by PhpStorm.
 * User: Happy233
 * Date: 2019/2/21
 * Time: 15:39
 */

namespace app\index\model;

use app\common\ModelSearch;
use think\db\Query;
use think\Model;

class Doctor extends Model
{
    use ModelSearch;

    protected $table = 'doctors';
    protected $autoWriteTimestamp = 'datetime';
    protected $pk = 'uid';

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
        return $this->belongsTo(User::class, 'uid', 'id');
    }

    public function team(){
        return $this->belongsTo(Team::class, 'team');
    }

    public function searchIdAttr(Query $query, $value)
    {
        $query->where('id', '=', $value);
    }

    public function searchNameAttr(Query $query, $value)
    {
        $this->searchAttrLike($query, 'name', $value);
    }

    public function searchStuIdAttr(Query $query, $value)
    {
        $this->searchAttrIn($query, 'stu_id', $value);
    }

    public function searchIdCodeAttr(Query $query, $value)
    {
        $this->searchAttrIn($query, 'id_code', $value);
    }

    public function searchTeamAttr(Query $query, $value)
    {
        $this->searchAttrLike($query, 'team.nick', $value);
    }

    public function searchPositionAttr(Query $query, $value)
    {
        $this->searchAttrIn($query, 'position', $value);
    }

}