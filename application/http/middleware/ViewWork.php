<?php

namespace app\http\middleware;

use app\index\model\User;

class ViewWork extends PermissionCheck
{

    public function check(User $user): bool
    {
        return $user->canViewUser();
    }
}
