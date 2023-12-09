<?php

namespace App\Filament\Resources\PaidHolidayResource\Pages;

use App\Filament\Resources\PaidHolidayResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPaidHolidays extends ListRecords
{
    protected static string $resource = PaidHolidayResource::class;

    protected function getActions(): array
    {
        return [];
    }
}
