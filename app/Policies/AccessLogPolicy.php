<?php

namespace App\Policies;

use App\Models\AccessLog;
use App\Models\User;

class AccessLogPolicy
{
    // メニューバーの表示
    public function viewAny(User $user)
    {
        return $user->id === 1;
    }

    // 詳細画面の表示
    public function view(User $user, AccessLog $accessLog)
    {
        return $user->id === 1;
    }
}