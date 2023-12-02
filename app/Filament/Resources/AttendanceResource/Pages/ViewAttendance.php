<?php

namespace App\Filament\Resources\AttendanceResource\Pages;

use App\Filament\Resources\AttendanceResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAttendance extends ViewRecord
{
    protected static string $resource = AttendanceResource::class;

    protected static ?string $title = '勤怠情報詳細';

    protected function getActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
