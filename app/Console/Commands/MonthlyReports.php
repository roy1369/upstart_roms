<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use App\Models\BatchLog;
use App\Models\User;
use App\Models\MonthlyReport;
use App\Services\BatchLogService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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
        // 現在日時を取得
        $now = Carbon::now();

        // バッチ開始日時を保存
        $batchLog = new BatchLog(['batch_name' => '月報確定バッチ', 'start_date_and_time' => $now]);
        $batchLog->save();

        $endingKubun = '';
        $errorStackTrace = '';
        $message = '';

        // 先月の開始日時と終了日時を取得
        $startOfMonth = now()->startOfMonth()->subMonth();
        $endOfMonth = now()->startOfMonth()->subSecond();

        // ユーザーテーブルからレコードをすべて取得する
        $users = User::get();

        // バッチが正常に終了した場合
        $endingKubun = '正常';

        foreach ($users as $user) {
            try {
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

                // メールでの通知処理
                // アドレスを格納
                $email = $user->email;
                $emailCc = config('services.mail.cc');

                // 件名をカスタマイズ
                $subject = '月報確定のご案内[株式会社UPSTART]';

                // 本文をカスタマイズ
                $messageText = "拝啓、お世話になっております。株式会社UPSTART勤怠管理システムサポートでございます。
                    \nいつもご利用いただき、誠にありがとうございます。 $user->name 様の勤怠月報が確定いたしましたことをお知らせいたします。
                    \n\n月報の詳細に関しては、ログイン後のダッシュボード、月報管理からご確認いただけます。
                    \n\n何かご不明点やご質問がございましたら、お気軽にお問い合わせください。今後とも株式会社UPSTART勤怠管理システムをよろしくお願いいたします。
                    \nお忙しい中、このメールをお読みいただき、誠にありがとうございます。
                    \n\n敬具
                    \n\n株式会社UPSTART勤怠管理システムサポート";
            
                // 通知メールを送信
                Mail::raw($messageText, function ($message) use ($email, $subject, $emailCc) {
                    $message->to($email);
                    $message->cc($emailCc);
                    $message->subject($subject);
                });

            } catch (\Exception $e) {
                // エラーが発生した場合、エラースタックトレースをログに記録する
                Log::error('バッチ実行中にエラーが発生しました。', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
                // エラースタックトレースと区分とメッセージを取得
                $endingKubun = 'エラー';
                $message .= $e->getMessage();
                $errorStackTrace .= $e->getTraceAsString();
            }
        }

        //BatchLogServiceでバッチログに保存
        BatchLogService::save($batchLog, $endingKubun, $message, $errorStackTrace);
    }
}
