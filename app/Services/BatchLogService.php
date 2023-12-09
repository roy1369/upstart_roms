<?php

namespace App\Services;

use Carbon\CarbonImmutable;

class BatchLogService
{
    // バッチログテーブルに保存処理
    public static function save($batchLog, $ending_kubun, $message, $error_stack_trace)
    {
        $batchLog->update([
            'ending_kubun' => $ending_kubun,
            'message' => $message,
            'error_stack_trace' => $error_stack_trace,
            'ending_date_and_time' => CarbonImmutable::now(),
        ]);
    }

}