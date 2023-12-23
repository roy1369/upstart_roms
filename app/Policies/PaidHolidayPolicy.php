<?php

namespace App\Policies;

use App\Models\User;

class PaidHolidayPolicy
{
    // メニューバーの表示
    public function viewAny(User $user)
    {
        return $user->full_time_authority === 1;
    }
}
