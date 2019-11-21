<?php
/**
 * 开心树上开心果, 开心树下你和我
 * User: happy233
 * Date: 2019-05-14
 * Time: 17:03
 */

namespace app\index\model;

use app\common\ModelSearch;
use think\db\Query;
use think\Model;

class TDoctor extends Model
{

    use ModelSearch;

    protected $table = 'doctors_wt';
    protected $pk = 'uid';

    public function searchIdAttr(Query $query, $value) {
        $query->where('id', '=', $value);
    }

    public function searchNameAttr(Query $query, $value) {
        $this->searchAttrLike($query, 'name', $value);
    }

    public function searchStuIdAttr(Query $query, $value) {
        $this->searchAttrIn($query, 'stu_id', $value);
    }

    public function searchIdCodeAttr(Query $query, $value) {
        $this->searchAttrIn($query, 'id_code', $value);
    }

    public function searchTeamAttr(Query $query, $value) {
        $this->searchAttrLike($query, 'tnick', $value);
    }

    public function searchPositionAttr(Query $query, $value) {
        $this->searchAttrIn($query, 'position', $value);
    }

    public function getIdCodeAttr($value) {
        return (string)$value;
    }

    public function getStuIdAttr($value) {
        return (string)$value;
    }

}