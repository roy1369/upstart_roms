<?php

namespace App\Filament\Resources\VariousRequestResource\Pages;

use App\Filament\Resources\VariousRequestResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewVariousRequest extends ViewRecord
{
    protected static string $resource = VariousRequestResource::class;

    protected static ?string $title = '各種申請詳細';

    protected function getActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
