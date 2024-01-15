<?php

namespace App\Filament\Resources\AttendanceResource\Pages;

use App\Filament\Resources\AttendanceResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAttendance extends EditRecord
{
    protected static string $resource = AttendanceResource::class;

    protected static ?string $title = '勤怠情報編集';

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->requiresConfirmation()
                ->modalHeading('削除確認')
                ->modalsubheading('本当に削除しますか？')
        ];
    }

    protected function getRedirectUrl(): string
    {
        // リダイレクト先を/attendancemanagement/attendancesに設定する
        return $this->previousUrl ?? url('/attendancemanagement/attendances');
    }
}
