<?php

namespace App\Filament\Resources\VariousRequestResource\Pages;

use App\Filament\Resources\VariousRequestResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVariousRequest extends EditRecord
{
    protected static string $resource = VariousRequestResource::class;

    protected function getActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
