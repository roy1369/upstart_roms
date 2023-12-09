<?php

namespace App\Filament\Resources\AccessLogResource\Pages;

use App\Filament\Resources\AccessLogResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAccessLog extends ViewRecord
{
    protected static string $resource = AccessLogResource::class;

    protected static ?string $title = 'アクセスログ詳細';

    protected function getActions(): array
    {
        return [];
    }
}