<?php

namespace App\Filament\Resources\AttendanceResource\Pages;

use App\Filament\Resources\AttendanceResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAttendances extends ListRecords
{
    protected static string $resource = AttendanceResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('出勤'),
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

}
