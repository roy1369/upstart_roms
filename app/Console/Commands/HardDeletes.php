<?php

namespace App\Console\Commands;

use App\Models\BatchLog;
use App\Services\BatchLogService;
use Carbon\CarbonImmutable;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class HardDeletes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:hard-deletes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '1年前のレコードを物理削除するバッチ';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // バッチ開始日時を保存
        $batchLog = new BatchLog([
            'batch_name' => '物理削除バッチ', 
            'start_date_and_time' => CarbonImmutable::now()
        ]);

        $batchLog->save();

        $endingKubun = '';
        $errorStackTrace = '';
        $message = '';
   
        // バッチが正常に終了した場合
        $endingKubun = '正常';

        $date = CarbonImmutable::now(); // 処理実行日
        // 今日から1年前の日付を取得
        $startDate = $date->subYear();

        try{
            // テーブルを配列に取得する
            $tables = [
                'AccessLog', 
                'Attendance', 
                'BatchLog', 
                'VariousRequest', 

            ];
            // ユーザーに関連するテーブルを配列に取得する
            $users = [
                'User', 
                'PaidHoliday',
                'MonthlyReport',
                'Address',
                'Attendance', 
                'VariousRequest', 
            ];

            foreach ($tables as $table) {
                $model = app('App\\Models\\' . $table);
                // 各テーブルから順番に条件に合うユーザーレコードを物理削除
                $model::where('created_at', '<', $startDate)->forceDelete();
            }

            foreach ($users as $user) {
                $logModel = app('App\\Models\\' . $user);
                // ユーザーに関連する各テーブルから条件に合うレコードを物理削除
                $logModel::where('deleted_at', '<', $startDate)->withTrashed()->forceDelete();
            }


        } catch (\Exception $e) {
            // エラーが発生した場合、エラースタックトレースをログに記録する
            Log::error('バッチ実行中にエラーが発生しました。', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            // エラースタックトレースと区分とメッセージを取得
            $endingKubun = 'エラー';
            $message .= $e->getMessage();
            $errorStackTrace .= $e->getTraceAsString();
        }

        //BatchLogServiceでバッチログに保存
        BatchLogService::save($batchLog, $endingKubun, $message, $errorStackTrace);
    }
}
