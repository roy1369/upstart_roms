<?php

namespace App\Policies;

use App\Models\BatchLog;
use App\Models\User;

class BatchLogPolicy
{
    // メニューバーの表示
    public function viewAny(User $user)
    {
        return $user->authority === 1;
    }

    // 詳細画面の表示
    public function view(User $user, BatchLog $batchLog)
    {
        return $user->authority === 1;
    }
}