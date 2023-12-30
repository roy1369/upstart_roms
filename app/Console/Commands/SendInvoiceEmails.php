<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Models\BatchLog;
use App\Services\BatchLogService;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\IOFactory;

class SendInvoiceEmails extends Command
{
    protected $signature = 'app:send-invoice-emails';
    protected $description = '毎月初めに顧客に請求書メールを送信する';

    public function handle()
    {
        // バッチ開始日時を保存
        $batchLog = new BatchLog(['batch_name' => '月次請求バッチ', 'start_date_and_time' => Carbon::now()]);
        $batchLog->save();

        $endingKubun = '';
        $errorStackTrace = '';
        $message = '';

        // バッチが正常に終了した場合
        $endingKubun = '正常';
   
        $date = Carbon::now(); // 処理実行日

        // テンプレートファイルのパスを取得
        $templatePath = storage_path('app/public/templates/invoice.xlsx');

        // 日付を取得してファイル名に使用する
        $currentDateTime = $date->format('Y年m月');
        $newFileName = '【御請求書】株式会社upstart様（システム運営）_' . $currentDateTime . '.xlsx';

        try{
            // テンプレートをコピーして新しいファイルを作成
            $newFilePath = storage_path('app/public/invoices/'. $newFileName);
            if (!copy($templatePath, $newFilePath)) {
                // コピーに失敗した場合のエラーハンドリング
                Log::error('テンプレートファイルのコピーに失敗しました');
                throw new Exception();
            }

            // コピーしたファイルを読み込む
            $spreadsheet = IOFactory::load($newFilePath);
            $worksheet = $spreadsheet->getActiveSheet();
        
            // 請求金額をコピーしたファイルの該当セルに挿入
            $worksheet->setCellValue('I1', $date->format('Y年m月d日'));
            $worksheet->setCellValue('A31', 'お支払い期限：'. $date->format('m月'). '末日');

            // 変更を保存
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save($newFilePath);

            // ExcelファイルをPDFに変換 
            if (file_exists($newFilePath)) {
                if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                    $cmd = 'set PATH=%PATH%;C:\Program Files\LibreOffice\program\; && soffice.com --headless --convert-to pdf --outdir ' . storage_path('app/public/invoices') . ' ' . $newFilePath;
                } else {
                    $cmd = 'export HOME=/tmp; libreoffice --headless --convert-to pdf --outdir ' . storage_path('app/public/invoices') . ' ' . $newFilePath;
                }
                exec($cmd);
            }
            
            // 生成されたPDFファイルのパスを取得
            $generatedPDFFileName = pathinfo($newFilePath, PATHINFO_FILENAME) . '.pdf';
            $pdfFilePath = storage_path('app/public/invoices/' . $generatedPDFFileName);

            // アドレスを格納
            $email = config('services.mail.to');
            $emailCc = config('services.mail.cc');

            // 件名をカスタマイズ
            $subject = '請求書のご案内[株式会社upstart]';

            // 本文をカスタマイズ
            $messageText = "拝啓、お世話になっております。株式会社upstart勤怠管理システムサポートでございます。
                \nいつもご利用いただき、誠にありがとうございます。 請求書が確定いたしましたことをお知らせいたします。
                \n\n何かご不明点やご質問がございましたら、お気軽にお問い合わせください。今後とも株式会社upstart勤怠管理システムをよろしくお願いいたします。
                \nお忙しい中、このメールをお読みいただき、誠にありがとうございます。
                \n\n敬具
                \n\n株式会社upstart勤怠管理システムサポート";
        
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

        //BatchLogServiceでバッチログに保存
        BatchLogService::save($batchLog, $endingKubun, $message, $errorStackTrace);
    }
}