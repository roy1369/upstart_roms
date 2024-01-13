<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VariousRequestResource\Pages;
use App\Filament\Resources\VariousRequestResource\RelationManagers;
use App\Models\Attendance;
use App\Models\PaidHoliday;
use App\Models\VariousRequest;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class VariousRequestResource extends Resource
{
    protected static ?string $model = VariousRequest::class;
    // ページタイトル
    protected static ?string $pluralModelLabel = '各種申請';
    // ページアイコン
    protected static ?string $navigationIcon = 'heroicon-o-pencil-alt';
    // ナビゲーションの名前
    protected static ?string $navigationLabel = '各種申請管理';
    // ナビゲーションの並び替え
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(1)
                    ->schema([
                        DatePicker::make('result')
                            ->label('申請日')
                            ->displayFormat('Y/m/d')
                            ->required(),
                        Select::make('type')
                            ->label('申請種別')
                            ->required()
                            ->placeholder('申請種別の選択')
                            ->options(config('services.type')),
                        TextInput::make('correction_working_address')
                            ->label('修正勤務先')
                            ->placeholder('打刻修正の場合のみ選択'),
                        Select::make('correction_working_type')
                            ->label('修正勤務形態')
                            ->placeholder('打刻修正の場合のみ選択')
                            ->options(config('services.workingType')),
                        TimePicker::make('correction_start_time')
                            ->label('修正出勤時間')
                            ->placeholder('打刻修正の場合のみ入力'),
                        TimePicker::make('correction_end_time')
                            ->label('修正退勤時間')
                            ->placeholder('打刻修正の場合のみ入力'),
                        TextInput::make('correction_transportation_expenses')
                            ->label('修正交通費')
                            ->placeholder('交通費修正の場合のみ往復分で入力')
                            ->numeric()
                            ->suffix('円'),
                        Textarea::make('comment')
                            ->label('申請理由')
                            ->required(),
                        
                    ]),
            ]);
            
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Split::make([
                    ViewColumn::make('user_id')
                        ->label('氏名')
                        ->hidden((! auth()->user()->authority))
                        ->toggleable(isToggledHiddenByDefault: false)
                        ->view('tables.columns.user-name-switcher')
                        ->searchable(query: function (Builder $query, string $search): Builder {
                            return $query->whereHas('user', function (Builder $subQuery) use ($search) {
                                $subQuery->where('name', 'like', "%{$search}%");
                            });
                        }),
                    TextColumn::make('result')
                        ->label('申請日')
                        ->searchable()
                        ->toggleable(isToggledHiddenByDefault: false)
                        ->dateTime('Y年m月d日'),
                    ViewColumn::make('type')
                        ->label('申請種別')
                        ->toggleable(isToggledHiddenByDefault: false)
                        ->view('tables.columns.type-switcher'),
                    ViewColumn::make('correction_start_time')
                        ->label('修正出勤時間')
                        ->toggleable(isToggledHiddenByDefault: false)
                        ->view('tables.columns.start-time-switcher'),
                    ViewColumn::make('correction_end_time')
                        ->label('修正退勤時間')
                        ->toggleable(isToggledHiddenByDefault: false)
                        ->view('tables.columns.end-time-switcher'),
                    TextColumn::make('correction_transportation_expenses')
                        ->label('修正交通費')
                        ->toggleable(isToggledHiddenByDefault: false)
                        ->money('jpy'),
                    TextColumn::make('comment')
                        ->label('申請理由')
                        ->toggleable(isToggledHiddenByDefault: false),
                ])->from('md')
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('application')
                    ->label('申請中')
                    ->button()
                    ->color('primary')
                    ->visible(
                        function ($record) :bool{
                            $ret = false;
                            // 申請状況が申請中なら表示処理
                            if ($record['status'] === 0) {
                                $ret = true;
                            }
                            return $ret;
                        }
                    ),
                Action::make('application_end')
                    ->label('承認済み')
                    ->button()
                    ->color('success')
                    ->visible(
                        function ($record) :bool{
                            $ret = false;
                            // 申請状況が承認済みなら表示処理
                            if ($record['status'] === 1) {
                                $ret = true;
                            }
                            return $ret;
                        }
                    ),
                Action::make('cancel_end')
                    ->label('却下済み')
                    ->button()
                    ->color('danger')
                    ->visible(
                        function ($record) :bool{
                            $ret = false;
                            // 申請状況が却下なら表示処理
                            if ($record['status'] === 2) {
                                $ret = true;
                            }
                            return $ret;
                        }
                    ),

                DeleteAction::make()
                    ->label('申請取消')
                    ->button()
                    ->icon('')
                    ->requiresConfirmation()
                    ->modalHeading('申請取消')
                    ->modalsubheading('本当に取消しますか？')
                    ->visible(
                        function ($record) :bool{
                            $ret = false;
                            // 申請状況が申請中なら表示処理
                            if ($record['status'] === 0) {
                                $ret = true;
                            }
                            return $ret;
                        }
                    ),

                // 以下は管理者権限のみ表示するアクション
                Action::make('approval')
                    ->label('承認')
                    ->button()
                    ->color('secondary')
                    ->requiresConfirmation()
                    ->modalHeading('承認確認')
                    ->modalsubheading('本当に承認しますか？')
                    ->action(
                        function ($record){
                            // 承認時の処理を個々に記述
                            switch ($record['type']) {
                                case 0:
                                    // 打刻修正の場合の処理
                                    $attendance = Attendance::where('user_id', $record['user_id'])
                                        ->whereDate('date', '=', $record['result']) 
                                        ->first();

                                        if (is_null($attendance)) {
                                            // 該当のレコードがないので新規で作成する処理
                                            $newAttendance = new Attendance();
                                            $newAttendance->user_id = $record['user_id'];
                                            $newAttendance->date = $record['result'];
                                            $newAttendance->start_time = $record['correction_start_time'];
                                            $newAttendance->working_address = $record['correction_working_address'];
                                            $newAttendance->working_type = $record['correction_working_type'];
                                            $newAttendance->end_time = $record['correction_end_time'];

                                            // 保存
                                            $newAttendance->save(); 
                                        } else {
                                            // 該当のレコードを更新する処理
                                            $attendance->start_time = $record['correction_start_time'];
                                            $attendance->working_address = $record['correction_working_address'];
                                            $attendance->working_type = $record['correction_working_type'];
                                            $attendance->end_time = $record['correction_end_time'];

                                            // 保存
                                            $attendance->save();
                                        }

                                        $record['status'] = 1;
                                        $record->save();
                                    break;
                                
                                case 1:
                                    // 有給申請の場合の処理
                                    $paidHoliday = PaidHoliday::where('user_id', $record['user_id'])
                                        ->first();

                                    $paidHoliday->amount -= 1;

                                    // 保存
                                    $paidHoliday->save();

                                    $record['status'] = 1;
                                    $record->save();

                                    break;
                            
                                case 2:
                                    // 交通費申請の場合の処理
                                    $attendance = Attendance::where('user_id', $record['user_id'])
                                        ->whereDate('date', '=', $record['result']) 
                                        ->first();

                                        if (!is_null($attendance)) {
                                            // 該当のレコードを更新する処理
                                            $attendance->transportation_expenses = $record['correction_transportation_expenses'];

                                            // 保存
                                            $attendance->save();

                                            $record['status'] = 1;
                                            $record->save();
                                        } else {
                                            $record['status'] = 2;
                                            $record->save();

                                        }

                                    break;
                                        
                                default:
                                    // 
                                    break;
                            }

                        }
                        
                    )
                    // 申請状況が申請中かつ管理者なら表示処理
                    ->hidden(! auth()->user()->authority)
                    ->visible(
                        function ($record) :bool{
                            $ret = false;
                            if ($record['status'] === 0) {
                                $ret = true;
                            }
                            return $ret;
                        }
                    ),
                Action::make('cancel')
                    ->label('却下')
                    ->button()
                    ->color('secondary')
                    ->requiresConfirmation()
                    ->modalHeading('却下確認')
                    ->modalsubheading('本当に却下しますか？')
                    ->action(
                        function ($record){
                            $record['status'] = 2;
                            $record->save();

                        }
                    )
                    // 申請状況が申請中かつ管理者なら表示処理
                    ->hidden(! auth()->user()->authority)
                    ->visible(
                        function ($record) :bool{
                            $ret = false;
                            if ($record['status'] === 0) {
                                $ret = true;
                            }
                            return $ret;
                        }
                    ),
                    
            ])
            ->bulkActions([
                // 
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVariousRequests::route('/'),
            'create' => Pages\CreateVariousRequest::route('/create'),
        ];
    }   
    
    public static function getEloquentQuery(): Builder
    {
        // 権限がない場合の処理
        if (auth()->user()->authority !== 1) {
            return parent::getEloquentQuery()
                ->where('user_id', Auth::id());
        // 権限がある場合の処理
        } else {
            return parent::getEloquentQuery();
        }
    }

}
