<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use App\Models\User;
use App\Models\MonthlyReport;
use Carbon\Carbon;
use Illuminate\Console\Command;

class MonthlyReports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:monthly-reports';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '月報確定バッチ';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // 先月の開始日時と終了日時を取得
        $startOfMonth = now()->startOfMonth()->subMonth();
        $endOfMonth = now()->startOfMonth()->subSecond();

        // ユーザーテーブルからレコードをすべて取得する
        $users = User::get();

        foreach ($users as $user) {
            // Attendancesテーブルから該当のユーザーで日付が先月のものを取得する
            $attendances = Attendance::where('user_id', $user->id)
                ->whereBetween('date', [$startOfMonth, $endOfMonth])
                ->get();

                
            // working_timeカラムの値を合計する
            $totalWorkingTimeInSeconds = 0;
            $totalOverTimeInSeconds = 0;

            $workingDayCount = 0;
            foreach ($attendances as $attendance) {
                // Carbonを使用して正確な時間の合計を計算
                $totalWorkingTimeInSeconds += Carbon::parse($attendance->working_time)->diffInSeconds(Carbon::parse('00:00:00'));
                if (!is_null($attendance->over_time)) {
                    $totalOverTimeInSeconds += Carbon::parse($attendance->over_time)->diffInSeconds(Carbon::parse('00:00:00'));
                }
                // 日数のカウントを1足す
                $workingDayCount += 1;
            }

            // 秒を時間に変換
            $totalWorkingTime = sprintf('%02d:%02d:%02d', ($totalWorkingTimeInSeconds / 3600), ($totalWorkingTimeInSeconds / 60 % 60), $totalWorkingTimeInSeconds % 60);
            $totalOverTime = sprintf('%02d:%02d:%02d', ($totalOverTimeInSeconds / 3600), ($totalOverTimeInSeconds / 60 % 60), $totalOverTimeInSeconds % 60);
            
            // 同日の月報があれば取得する
            $monthlyRepot = MonthlyReport::where('user_id', $user->id)
                ->whereYear('date', now()->year)
                ->whereMonth('date', now()->month)
                ->whereDay('date', now()->day)
                ->first();
            
                // 同日の月報がなければ新しく作成する
            if (is_null($monthlyRepot)) {
                // 新しいレコードを作成する
                $newMonthlyReport = new MonthlyReport();
                $newMonthlyReport->user_id = $user->id;
                $newMonthlyReport->date = now();
                $newMonthlyReport->total_working_time = $totalWorkingTime;
                $newMonthlyReport->total_over_time = $totalOverTime;
                $newMonthlyReport->num_working_days = $workingDayCount;

                // 保存
                $newMonthlyReport->save(); 
            }
        }
    }
}
