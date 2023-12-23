<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\PaidHoliday;
use Carbon\Carbon;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected static ?string $title = 'アカウント情報編集';

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->requiresConfirmation()
                ->modalHeading('削除確認')
                ->modalsubheading('本当に削除しますか？')
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $paidHoliday = PaidHoliday::where('user_id', $data['id'])->first();
        if (is_null($paidHoliday)) {
            // 有給管理テーブルに該当のレコードがない場合
            if ($data['full_time_authority'] === 1) {
                // 正社員権限がある場合

                // joining_dateをCarbonインスタンスに変換
                $joiningDate = Carbon::parse($data['joining_date']);
                
                // 6ヶ月後の日付を計算
                $nextPaidHoliday = $joiningDate->copy()->addMonths(6)->startOfMonth();
                
                // 有給管理テーブルにレコードを作成
                $paidHoliday = new PaidHoliday;
                $paidHoliday->user_id = $data['id'];
                $paidHoliday->amount = 0;
                $paidHoliday->next_paid_holiday = $nextPaidHoliday;
                $paidHoliday->save();

            }
        } 
    
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        // リダイレクト先を/admin/loginに設定する
        return $this->previousUrl ?? url('/admin/login');
    }

}
