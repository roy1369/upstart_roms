<?php

namespace App\Filament\Resources\BatchLogResource\Pages;

use App\Filament\Resources\BatchLogResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBatchLogs extends ListRecords
{
    protected static string $resource = BatchLogResource::class;

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