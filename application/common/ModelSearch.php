<?php
/**
 * Created by PhpStorm.
 * User: Happy233
 * Date: 2019/3/14
 * Time: 15:39
 */

namespace app\common;


use think\db\Query;

trait ModelSearch
{

    public function searchAttrEqual(Query $query, $key, $value) {
        if (!empty($value) || $value == '0')
            $query->where($key, '=', $value);
    }

    public function searchAttrIn(Query $query, $key, $value) {
        $value = explode(',', $value);
        if (empty($value)) return;
        if (count($value) == 1) {
            $query->where($key, '=', $value[0]);
        } else {
            sort($value);
            $query->whereIn($key, $value);
        }
    }

    public function searchAttrLike(Query $query, $key, $value) {
        if (!empty($value) || $value == '0') {
            $value = trim($value);
            $query->whereLike($key, "%{$value}%");
        }
    }

    public function searchAttrBetween(Query $query, $key, $value) {
        if (count($value) >= 1 && (!empty($value[0]) || $value[0] == '0')) {
            $query->where($key, '>=', trim($value[0]));
        }
        if (count($value) >= 2 && (!empty($value[1]) || $value[1] == '0')) {
            $query->where($key, '<=', trim($value[1]));
        }
    }
}