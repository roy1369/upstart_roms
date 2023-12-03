<?php

namespace App\Filament\Resources\VariousRequestResource\Pages;

use App\Filament\Resources\VariousRequestResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateVariousRequest extends CreateRecord
{
    protected static string $resource = VariousRequestResource::class;

    protected static ?string $title = '各種申請作成';

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // 現在ログイン中のユーザーを取得する
        $currentUser = Auth::user();
        // ユーザーIDを格納
        $data['user_id'] = $currentUser->id;
        // 申請ステータスを格納
        $data['status'] = 0;
    
        return $data;
    }

}
