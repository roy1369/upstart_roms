<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\softDeletes;

class Attendance extends Model
{
    use HasFactory;
    use softDeletes;

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    // ユーザーテーブル
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
}
