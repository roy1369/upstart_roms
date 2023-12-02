<?php

namespace App\Filament\Resources\AttendanceResource\Pages;

use App\Filament\Resources\AttendanceResource;
use Filament\Pages\Actions;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Pages\CreateRecord;

class CreateAttendance extends CreateRecord
{
    protected static string $resource = AttendanceResource::class;

    protected static ?string $title = '勤怠情報作成';
    // 保存して続けて作成ボタンの削除
    protected static bool $canCreateAnother = false;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // 現在ログイン中のユーザーを取得する
        $currentUser = Auth::user();
        // ユーザーIDを格納
        $data['user_id'] = $currentUser->id;
        // 日付を格納
        $data['date'] = now();
        // 出勤時間に現在の時刻を格納
        $data['start_time'] = now();
        // 出勤場所に住所を格納　※あとで現在の位置情報を取得できるように修正
        $data['start_address'] = '猫県猫市猫谷町２－２２';
    
        return $data;
    }
}
