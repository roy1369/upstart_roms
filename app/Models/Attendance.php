<?php

namespace App\Models;

use Carbon\Carbon;
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

    protected $fillable = [
        'user_id',
        'date',
        'start_address',
        'start_time',
        'working_address',
        'working_type',
        'end_address', 
        'end_time',
        'start_station',
        'end_station',
        'transportation_expenses',
        'working_time',
        'rest_time',
        'over_time',
    ];

    // ユーザーテーブル
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($attendance) {
            $start_time = Carbon::parse($attendance->start_time);
            $end_time = Carbon::parse($attendance->end_time);
            $working_type = $attendance->working_type;

            // working_typeが0の場合、start_timeを09:00:00より前なら09:00:00に、後ならそのまま使用
            if ($working_type === 0 && $start_time->lt(Carbon::parse('09:00:00'))) {
                $start_time = Carbon::parse('09:00:00');
            }

            // working_typeが1の場合、start_timeを10:00:00より前なら10:00:00に、後ならそのまま使用
            if ($working_type === 1 && $start_time->lt(Carbon::parse('10:00:00'))) {
                $start_time = Carbon::parse('10:00:00');
            }

            // working_typeが2の場合、start_timeを11:00:00より前なら11:00:00に、後ならそのまま使用
            if ($working_type === 2 && $start_time->lt(Carbon::parse('11:00:00'))) {
                $start_time = Carbon::parse('11:00:00');
            }

            // working_typeが3の場合、start_timeを12:00:00より前なら12:00:00に、後ならそのまま使用
            if ($working_type === 3 && $start_time->lt(Carbon::parse('12:00:00'))) {
                $start_time = Carbon::parse('12:00:00');
            }
            
            // 差分を計算して 15 分刻みに調整
            $diffInMinutes = $end_time->diffInMinutes($start_time);
            $roundedDiffInMinutes = floor($diffInMinutes / 15) * 15;
            $working_time = gmdate('H:i:s', $roundedDiffInMinutes * 60);

            // 休憩時間の初期値
            $rest_time = '00:00:00';

            // 残業時間の初期化
            $over_time = '00:00:00';

            // 差分が9時間以上の場合は休憩時間の1時間を差し引く
            // 6時間45分以上9時間未満の場合は休憩時間の45分を差し引く
            if ($roundedDiffInMinutes >= 540) {
                $working_time = gmdate('H:i:s', ($roundedDiffInMinutes - 60) * 60);
                $over_time = gmdate('H:i:s', ($roundedDiffInMinutes - 540) * 60);
                $rest_time = '01:00:00';
            } elseif ($roundedDiffInMinutes >= 405) {
                $working_time = gmdate('H:i:s', ($roundedDiffInMinutes - 45) * 60);
                $rest_time = '00:45:00';
            } 

            $attendance->working_time = $working_time;
            $attendance->rest_time = $rest_time;
            $attendance->over_time = $over_time;
        });
    }

}
