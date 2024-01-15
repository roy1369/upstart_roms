<?php

namespace App\Filament\Resources\AttendanceResource\Pages;

use Carbon\Carbon;
use App\Filament\Resources\AttendanceResource;
use App\Models\Address;
use App\Models\Attendance;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Pages\Actions\Action;
use Illuminate\Support\Facades\Auth;

class ListAttendances extends ListRecords
{
    protected static string $resource = AttendanceResource::class;

    protected function getActions(): array
    {
        // 現在ログイン中のユーザーを取得する
        $currentUser = Auth::user();

        // 本日の日付を取得
        $today = now()->toDateString();

        // 本日の日付かつログインユーザーに関連するattendanceレコードを取得
        $attendanceRecord = Attendance::where('user_id', $currentUser->id)
        ->whereDate('date', $today)
        ->first();

        return [
            Actions\CreateAction::make()
                ->label('出勤')
                ->visible(
                    function () use ($attendanceRecord) {
                        // $attendanceRecordがNULLなら表示
                        return is_null($attendanceRecord);
                    }
                ),
            Action::make('end')
                ->label('退勤')
                ->button()
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('退勤確認')
                ->modalsubheading('本当に退勤しますか？')
                ->action(
                    function () use ($attendanceRecord) {

                        // \Log::debug(print_r($attendanceRecord, true));
                        // 退勤時刻の保存処理
                        $start_time = Carbon::parse($attendanceRecord->start_time);
                        $end_time = Carbon::parse($attendanceRecord->end_time);

                        // 差分を計算して HH:mm:ss 形式にフォーマット
                        $working_time = $end_time->diff($start_time)->format('%H:%I:%S');

                        // 現在の位置情報を取得する
                        $address = Address::where('user_id', $attendanceRecord['user_id'])->first();

                        $attendanceRecord->update([
                            'end_time' => $end_time,
                            'end_address' => $address->now_address,
                            'working_time' => $working_time,
                        ]);
                    }
                )
                ->visible(
                    function () use ($attendanceRecord) {
                        // $attendanceRecordがNULLでなく、かつend_timeがNULLであれば表示
                        return !is_null($attendanceRecord) && is_null($attendanceRecord->end_time);
                    }
                ),
        ];
    }

    public function getDefaultTableSortColumn(): ?string
    {
        return 'id';
    }

    public function getDefaultTableSortDirection(): ?string
    {
        return 'desc';
    }

    protected function getHeaderWidgets(): array
    {
        return [
            AttendanceResource\Widgets\AttendanceOverview::class,
        ];
    }

}
