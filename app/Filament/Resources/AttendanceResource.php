<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AttendanceResource\Pages;
use App\Filament\Resources\AttendanceResource\RelationManagers;
use App\Models\Attendance;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AttendanceResource extends Resource
{
    protected static ?string $model = Attendance::class;
    // ページタイトル
    protected static ?string $pluralModelLabel = '出退勤情報';
    // ページアイコン
    protected static ?string $navigationIcon = 'heroicon-o-collection';
    // ナビゲーションの名前
    protected static ?string $navigationLabel = '出退勤管理';
    // ナビゲーションの並び替え
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(1)
                    ->schema([
                        Select::make('working_address')
                            ->label('勤務先')
                            ->required()
                            ->placeholder('勤務先を選択')
                            ->options(config('services.workingAddress')),
                        Select::make('working_type')
                            ->label('勤務形態')
                            ->required()
                            ->placeholder('勤務形態を選択')
                            ->options(config('services.workingType')),
                        TimePicker::make('start_time')
                            ->label('出勤時間')
                            ->placeholder('自動入力')
                            ->hidden(! auth()->user()->authority)
                            ->disabled(! auth()->user()->authority),
                        // あとで現在の位置情報を取得できるように修正
                        TextInput::make('start_address')
                            ->label('出勤住所')
                            ->placeholder('自動入力')
                            ->hidden(! auth()->user()->authority)
                            ->disabled(! auth()->user()->authority),
                        // 退勤ボタンアクションで実行する
                        TimePicker::make('end_time')
                            ->label('退勤時間')
                            ->placeholder('自動入力')
                            ->hidden(! auth()->user()->authority)
                            ->disabled(! auth()->user()->authority),
                        // あとで現在の位置情報を取得できるように修正　退勤ボタンアクションで実行する
                        TextInput::make('end_address')
                            ->label('退勤住所')
                            ->placeholder('自動入力')
                            ->hidden(! auth()->user()->authority)
                            ->disabled(! auth()->user()->authority),
                        // 退勤ボタンアクションで実行する
                        TimePicker::make('working_time')
                            ->label('勤務時間')
                            ->placeholder('自動入力')
                            ->disabled(! auth()->user()->authority),
                        // 退勤ボタンアクションで実行する
                        TimePicker::make('rest_time')
                            ->label('休憩時間')
                            ->placeholder('自動入力')
                            ->disabled(! auth()->user()->authority),
                        // 退勤ボタンアクションで実行する
                        TimePicker::make('over_time')
                            ->label('残業時間')
                            ->placeholder('自動入力')
                            ->disabled(! auth()->user()->authority),
                        TextInput::make('transportation_expenses')
                            ->label('交通費')
                            ->placeholder('往復分で入力')
                            ->numeric()
                            ->suffix('円')
                            ->required()
                            ->hidden(! auth()->user()->transportation_expenses_flag),
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
                    ->view('tables.columns.user-name-switcher'),
                    TextColumn::make('date')
                        ->label('出勤日')
                        ->searchable()
                        ->toggleable(isToggledHiddenByDefault: false)
                        ->dateTime('Y年m月d日'),
                    ViewColumn::make('working_address')
                        ->label('勤務先')
                        ->toggleable(isToggledHiddenByDefault: false)
                        ->view('tables.columns.working-address-switcher'),
                    ViewColumn::make('working_type')
                        ->label('出勤形態')
                        ->toggleable(isToggledHiddenByDefault: false)
                        ->view('tables.columns.working-type-switcher'),
                    TextColumn::make('start_time')
                        ->label('出勤時間')
                        ->searchable()
                        ->toggleable(isToggledHiddenByDefault: false)
                        ->dateTime('G時i分'),
                    TextColumn::make('end_time')
                        ->label('退勤時間')
                        ->searchable()
                        ->toggleable(isToggledHiddenByDefault: false)
                        ->dateTime('G時i分'),
                ])->from('md')
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('end')
                    ->label('退勤')
                    ->button()
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('退勤確認')
                    ->modalsubheading('本当に退勤しますか？')
                    ->action(
                        function ($record){
                            // あとで退勤時刻と退勤場所の保存処理を追加

                        }
                    )
                    ->visible(
                        function ($record) :bool{
                            $ret = false;
                            // 退勤時間がNULLなら表示
                            if (is_null($record['end_time'])) {
                                $ret = true;
                            }
                            return $ret;
                        }
                    ),
                Action::make('endON')
                    ->label('退勤済')
                    ->button()
                    ->color('danger')
                    ->visible(
                        function ($record) :bool{
                            $ret = false;
                            // 退勤時間がNULLじゃなければ表示
                            if (!is_null($record['end_time'])) {
                                $ret = true;
                            }
                            return $ret;
                        }
                    ),
                ViewAction::make()
                    ->label('詳細')
                    ->icon('')
                    ->button(),
                EditAction::make()
                    ->label('編集')
                    ->icon('')
                    ->button(),
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
            'index' => Pages\ListAttendances::route('/'),
            'create' => Pages\CreateAttendance::route('/create'),
            'view' => Pages\ViewAttendance::route('/{record}'),
            'edit' => Pages\EditAttendance::route('/{record}/edit'),
        ];
    }    
}
