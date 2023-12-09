<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\PaidHoliday;
use Carbon\Carbon;

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
        // ユーザーテーブルからjoining_dateが現在から6ヶ月経過しているユーザーを取得
        $users = User::where(function ($query) {
            $query->whereDate('joining_date', '<=', Carbon::now()->subMonths(5));
        })
        ->get();

        foreach ($users as $user) {
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

            }
        }

    }
}
