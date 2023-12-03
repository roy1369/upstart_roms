<?php

namespace App\Filament\Resources\VariousRequestResource\Pages;

use App\Filament\Resources\VariousRequestResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVariousRequests extends ListRecords
{
    protected static string $resource = VariousRequestResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
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
