<?php
/**
 * Created by PhpStorm.
 * User: Happy233
 * Date: 2019/2/21
 * Time: 15:39
 */

namespace app\index\model;

use think\helper\Hash;
use think\Model;

class User extends Model
{
    protected $table = 'users';
    protected $autoWriteTimestamp = 'datetime';

    const NORMAL = 0; // 查看自己
    const USER_VIEWER = 1; // 查看用户
    const HOSPITAL_VIEWER = 2; // 可以查看Work
    const USER_MANAGER = 3; // 管理用户
    const HOSPITAL_MANAGER = 4; // 可以修改Work
    const ADMIN = 5;

    const permissions = [
        self::NORMAL,
        self::USER_VIEWER,
        self::HOSPITAL_VIEWER,
        self::USER_MANAGER,
        self::HOSPITAL_MANAGER,
        self::ADMIN,
    ];

    public static function userExists($options = [])
    {
        $user = (new self);
        $find = false;
        if (isset($options['nick'])) {
            $find = true;
            $user->whereOr('nick', $options['nick']);
        }
        if (isset($options['nick'])) {
            $find = true;
            $user->whereOr('nick', $options['nick']);
        }
        return $find && $user->count('id') > 0;
    }

    public function setPassword($value)
    {
        return Hash::make($value);
    }

    public function canViewUser(): bool
    {
        return $this->position >= 1;
    }

    public function canViewWork(): bool
    {
        return $this->position >= 2;
    }


    public function canEditUser(): bool
    {
        return $this->position >= 3;
    }


    public function canEditWork(): bool
    {
        return $this->position >= 4;
    }


    public function isAdmin(): bool
    {
        return $this->position == 5;
    }

    public function doctor()
    {
        return $this->hasOne(Doctor::class, 'id', 'id')->setEagerlyType(0);
    }

}