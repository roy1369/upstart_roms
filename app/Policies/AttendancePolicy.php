<?php

namespace App\Policies;

use App\Models\Attendance;
use App\Models\User;

class AttendancePolicy
{
    // 編集画面の制御
    public function update(User $user, Attendance $attendance) 
    {
        return $attendance->end_time === null;
    }
    // 削除機能の制御
    public function delete(User $user) 
    {
        return $user->authority === 1;
    }

}
