<?php

namespace App\Console\Commands;

use App\Models\BatchLog;
use App\Models\User;
use App\Models\PaidHoliday;
use App\Services\BatchLogService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class UpdatePaidHolidays extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-paid-holidays';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '半年ごとに有給を付与するバッチ';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // 現在日時を取得
        $now = Carbon::now();

        // バッチ開始日時を保存
        $batchLog = new BatchLog(['batch_name' => '有給付与バッチ', 'start_date_and_time' => $now]);
        $batchLog->save();

        $endingKubun = '';
        $errorStackTrace = '';
        $message = '';

        // ユーザーテーブルからjoining_dateが現在から6ヶ月経過しているユーザーを取得
        $users = User::where(function ($query) {
            $query->whereDate('joining_date', '<=', Carbon::now()->subMonths(5));
        })
        ->get();

        // バッチが正常に終了した場合
        $endingKubun = '正常';

        foreach ($users as $user) {
            try {
                // joining_dateからの経過月数を取得する
                $monthsSinceJoining = Carbon::parse($user->joining_date)->diffInMonths(Carbon::now());
                // joining_dateからの経過月数が6の倍数の場合にpaid_holidaysテーブルのamountを更新
                if ($monthsSinceJoining % 6 === 0) {
                    // 現在のamountに5を追加する
                    $amount = 5;
                    $amount += PaidHoliday::where('user_id', $user->id)->sum('amount');

                    // 次回の有給取得予定日を取得
                    $nextPaidHoliday = Carbon::parse($user->joining_date)
                        ->addMonths($monthsSinceJoining + 6) // 6ヶ月後を加算
                        ->startOfMonth(); // 月初めに設定

                    // レコードのアップデート
                    $paidHoliday = PaidHoliday::where('user_id', $user->id)->first();
                    $paidHoliday->amount = $amount;
                    $paidHoliday->next_paid_holiday = $nextPaidHoliday;
                    $paidHoliday->save();

                    // メールでの通知処理
                    // アドレスを格納
                    $email = $user->email;
                    $emailCc = config('services.mail.cc');
    
                    // 件名をカスタマイズ
                    $subject = '有給付与のご案内[株式会社upstart]';
    
                    // 本文をカスタマイズ
                    $messageText = "拝啓、お世話になっております。株式会社upstart勤怠管理システムサポートでございます。
                        \nいつもご利用いただき、誠にありがとうございます。有給休暇が付与されましたことをお知らせいたします。
                        \n\n有給の詳細に関しては、ログイン後のダッシュボード、有給管理からご確認いただけます。
                        \n\n何かご不明点やご質問がございましたら、お気軽にお問い合わせください。今後とも株式会社upstart勤怠管理システムをよろしくお願いいたします。
                        \nお忙しい中、このメールをお読みいただき、誠にありがとうございます。
                        \n\n敬具
                        \n\n株式会社upstart勤怠管理システムサポート";
                
                    // 通知メールを送信
                    Mail::raw($messageText, function ($message) use ($email, $subject, $emailCc) {
                        $message->to($email);
                        // $message->cc($emailCc);
                        $message->subject($subject);
                    });
                }


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
