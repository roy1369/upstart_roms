<?php

namespace App\Filament\Resources\BatchLogResource\Pages;

use App\Filament\Resources\BatchLogResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewBatchLog extends ViewRecord
{
    protected static string $resource = BatchLogResource::class;

    protected static ?string $title = 'バッチログ詳細';

    protected function getActions(): array
    {
        return [];
    }
}