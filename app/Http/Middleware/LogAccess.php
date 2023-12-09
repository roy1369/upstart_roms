<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\AccessLog;

class LogAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // アクセスログの情報を取得
        $logData = [
            'access_url' => $request->fullUrl(),
            'user_agent' => $request->header('User-Agent'),
            'form_value' => json_encode($request->all()), 
            'kubun' => $request->method(),
        ];

        // データベースに保存
        AccessLog::create($logData);

        return $next($request);
    }
}