<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // 毎月1日の10時に有給付与バッチを呼び出す
        $schedule->command('app:update-paid-holidays')->monthlyOn(1, '10:00');
        // 毎月1日の10時に月報バッチを呼び出す
        $schedule->command('app:monthly-reports')->monthlyOn(1, '10:00');
        // 毎月1日の10時に交通費レポートバッチを呼び出す
        $schedule->command('app:transportation-expenses-report')->monthlyOn(1, '10:00');
        // 毎月1日の10時に請求書バッチを呼び出す
        $schedule->command('app:send-invoice-emails')->monthlyOn(1, '10:00');
        // 毎日0時に物理削除バッチを呼び出す
        $schedule->command('app:hard-deletes')->daily();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
