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

    public function searchAttrEqual(Query $query, $key, $value)
    {
        if (!empty($value) || $value == '0')
            $query->where($key, '=', $value);
    }

    public function searchAttrIn(Query $query, $key, $value)
    {
        $value = explode(',', $value);
        if (empty($value)) return;
        if (count($value) == 1) {
            $query->where($key, '=', $value[0]);
        } else {
            sort($value);
            $query->whereIn($key, $value);
        }
    }

    public function searchAttrLike(Query $query, $key, $value)
    {

        if (!empty($value) || $value == '0')
            $query->whereLike($key, "%{$value}%");
    }

}