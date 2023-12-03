<?php

namespace App\Policies;

use App\Models\User;

class AttendancePolicy
{
    // // 編集画面の制御
    // public function update(User $user) 
    // {
    //     return $user->id === 1;
    // }
    // 削除機能の制御
    public function delete(User $user) 
    {
        return $user->id === 1;
    }

}
