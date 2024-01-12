<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'name_kana',
        'email',
        'password',
        'joining_date',
        'retirement_date',
        'authority',
        'full_time_authority',
        'transportation_expenses_flag',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // 勤怠情報テーブル
    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'user_id')->withTrashed();
    }

    // 有給管理テーブル
    public function paidHolidays()
    {
        return $this->hasMany(PaidHoliday::class, 'user_id')->withTrashed();
    }

    // 各種申請テーブル
    public function variousRequests()
    {
        return $this->hasMany(VariousRequest::class, 'user_id')->withTrashed();
    }

    // 月報情報テーブル
    public function monthlyReports()
    {
        return $this->hasMany(MonthlyReport::class, 'user_id')->withTrashed();
    }

    // 現在住所情報テーブル
    public function addresses()
    {
        return $this->hasMany(Address::class, 'user_id')->withTrashed();
    }

    // リレーション先のデータも削除
    public static function booted()
    {
        parent::boot();
        static::deleted(function ($user) {
            $user->attendances()->delete();
            $user->paidHolidays()->delete();
            $user->variousRequests()->delete();
            $user->monthlyReports()->delete();
            $user->addresses()->delete();
            
        });
    }
}
