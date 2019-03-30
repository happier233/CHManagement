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

class Work extends Model
{
    use ModelSearch;

    protected $table = 'works';
    protected $autoWriteTimestamp = 'datetime';

    public function doctor() {
        return $this->belongsTo(Doctor::class, 'doctor', 'uid')->bind([
            'name'
        ]);
    }

    public function detail() {
        return $this->hasOne(WorkDetail::class, 'wid', 'id');
    }

    public function searchIdAttr(Query $query, $value) {
        $this->searchAttrEqual($query, 'id', $value);
    }

    public function searchDoctorAttr(Query $query, $value) {
        $this->searchAttrLike($query, 'doctor.name', $value);
    }

    public function searchStartTimeAttr(Query $query, $value) {
        if (strpos($value, ',') === false) {
            $this->searchAttrEqual($query, 'start_time', $value);
        } else {
            $this->searchAttrBetween($query, 'start_time', explode(',', $value, 2));
        }
    }

    public function searchDurationAttr(Query $query, $value) {
        if (strpos($value, ',') === false) {
            $this->searchAttrEqual($query, 'duration', $value);
        } else {
            $this->searchAttrBetween($query, 'duration', explode(',', $value, 2));
        }
    }

    public function searchNameAttr(Query $query, $value) {
        $this->searchAttrLike($query, 'work_detail.name', $value);
    }

    public function searchCollegeAttr(Query $query, $value) {
        $this->searchAttrLike($query, 'work_detail.college', $value);
    }

    public function searchEvaluationTime(Query $query, $value) {
        if (strpos($value, ',') === false) {
            $this->searchAttrEqual($query, 'work_detail.evaluation', $value);
        } else {
            $this->searchAttrBetween($query, 'work_detail.evaluation', explode(',', $value, 2));
        }
    }

    public function searchConfirmTime(Query $query, $value) {
        if (strpos($value, ',') === false) {
            $this->searchAttrEqual($query, 'work_detail.confirm_time', $value);
        } else {
            $this->searchAttrBetween($query, 'work_detail.confirm_time', explode(',', $value, 2));
        }
    }
}