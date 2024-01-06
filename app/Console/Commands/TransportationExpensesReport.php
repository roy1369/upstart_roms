<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use App\Models\BatchLog;
use App\Models\User;
use App\Services\BatchLogService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class TransportationExpensesReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:transportation-expenses-report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '交通費レポートバッチ';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // 現在日時を取得
        $now = Carbon::now();

        // バッチ開始日時を保存
        $batchLog = new BatchLog(['batch_name' => '交通費レポートバッチ', 'start_date_and_time' => $now]);
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

                    
                // transportation-expensesカラムの値を合計する
                // テンプレートファイルのパスを取得する
                $templatePath = storage_path('app/public/templates/transportation_expenses_report.xlsx');
                // 日付を取得してファイル名に使用する
                $currentDateTime = $now->format('Y年m月d日');
                $newFileName = '【支払明細書】' . $user->name . '_交通費明細書_' . $currentDateTime . '.xlsx';

                // テンプレートをコピーして新しいファイルを作成
                $newFilePath = storage_path('app/public/transportation_expenses_reports/'. $newFileName);
                if (!copy($templatePath, $newFilePath)) {
                    // コピーに失敗した場合のエラーハンドリング
                    Log::error('テンプレートファイルのコピーに失敗しました');
                    throw new Exception();
                }

                // コピーしたファイルを読み込む
                $spreadsheet = IOFactory::load($newFilePath);
                $worksheet = $spreadsheet->getActiveSheet();
            
                // 後ほどセルの中身の編集を確認）
                $worksheet->setCellValue('I1', $now->format('Y年m月d日'));
                $worksheet->setCellValue('B5', $user->name);

                // 取得したレコードの数だけ日付をセルに保存
                $rowIndex = 8; // A8から開始
                foreach ($attendances as $attendance) {
                    $worksheet->setCellValue('A' . $rowIndex, $attendance->date);
                    $worksheet->setCellValue('D' . $rowIndex, $attendance->start_station);
                    $worksheet->setCellValue('G' . $rowIndex, $attendance->end_station);
                    $worksheet->setCellValue('I' . $rowIndex, $attendance->transportation_expenses);
                    $rowIndex++;
                }

                // 変更を保存
                $writer = new Xlsx($spreadsheet);
                $writer->save($newFilePath);

                // ExcelファイルをPDFに変換 
                if (file_exists($newFilePath)) {
                    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                        $cmd = 'set PATH=%PATH%;C:\Program Files\LibreOffice\program\; && soffice.com --headless --convert-to pdf --outdir ' . storage_path('app/public/transportation_expenses_reports') . ' ' . $newFilePath;
                    } else {
                        $cmd = 'export HOME=/tmp; libreoffice --headless --convert-to pdf --outdir ' . storage_path('app/public/transportation_expenses_reports') . ' ' . $newFilePath;
                    }
                    exec($cmd);
                }

                // 生成されたPDFファイルのパスを取得する
                $generatedPDFFileName = pathinfo($newFilePath, PATHINFO_FILENAME) . '.pdf';
                $pdfFilePath = storage_path('app/public/transportation_expenses_reports/' . $generatedPDFFileName);

                // アドレスを格納
                $email = config('services.mail.to');
                $emailCc = config('services.mail.cc');

                // 件名をカスタマイズ
                $subject = '交通費明細書のご案内[株式会社UPSTART]';

                // 本文をカスタマイズ
                $messageText = "拝啓、お世話になっております。株式会社UPSTART勤怠管理システムサポートでございます。
                    \nいつもご利用いただき、誠にありがとうございます。 $user->name 様の交通費明細書が確定いたしましたことをお知らせいたします。
                    \n\n何かご不明点やご質問がございましたら、お気軽にお問い合わせください。今後とも株式会社UPSTART勤怠管理システムをよろしくお願いいたします。
                    \nお忙しい中、このメールをお読みいただき、誠にありがとうございます。
                    \n\n敬具
                    \n\n株式会社UPSTART勤怠管理システムサポート";
            
                // 通知メールを送信
                Mail::raw($messageText, function ($message) use ($email, $subject, $emailCc, $pdfFilePath) {
                    $message->to($email);
                    // $message->cc($emailCc);
                    $message->subject($subject);
                    $message->attach($pdfFilePath);
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
