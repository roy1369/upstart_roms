<?php

namespace App\Filament\Resources\AccessLogResource\Pages;

use App\Filament\Resources\AccessLogResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAccessLogs extends ListRecords
{
    protected static string $resource = AccessLogResource::class;

    protected function getActions(): array
    {
        return [];
    }
    
    protected function getDefaultTableSortColumn(): ?string
    {
        return 'id';
    }
    
    protected function getDefaultTableSortDirection(): ?string
    {
        return 'desc';
    }
}