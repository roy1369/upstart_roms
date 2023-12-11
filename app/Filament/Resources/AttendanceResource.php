<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use App\Filament\Resources\AttendanceResource\Pages;
use App\Filament\Resources\AttendanceResource\RelationManagers;
use App\Models\Address;
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
use Torann\GeoIP\Facades\GeoIP;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
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
                            // ->disabled(! auth()->user()->authority),
                            ->disabled(),
                        // あとで現在の位置情報を取得できるように修正
                        TextInput::make('start_address')
                            ->label('出勤住所')
                            ->placeholder('自動入力')
                            ->hidden(! auth()->user()->authority)
                            // ->disabled(! auth()->user()->authority),
                            ->disabled(),
                        TimePicker::make('end_time')
                            ->label('退勤時間')
                            ->placeholder('自動入力')
                            ->hidden(! auth()->user()->authority)
                            // ->disabled(! auth()->user()->authority),
                            ->disabled(),
                        // あとで現在の位置情報を取得できるように修正　退勤ボタンアクションで実行する
                        TextInput::make('end_address')
                            ->label('退勤住所')
                            ->placeholder('自動入力')
                            ->hidden(! auth()->user()->authority)
                            // ->disabled(! auth()->user()->authority),
                            ->disabled(),
                        TimePicker::make('working_time')
                            ->label('勤務時間')
                            ->placeholder('自動入力')
                            // ->disabled(! auth()->user()->authority),
                            ->disabled(),
                        TimePicker::make('rest_time')
                            ->label('休憩時間')
                            ->placeholder('自動入力')
                            // ->disabled(! auth()->user()->authority),
                            ->disabled(),
                        TimePicker::make('over_time')
                            ->label('残業時間')
                            ->placeholder('自動入力')
                            // ->disabled(! auth()->user()->authority),
                            ->disabled(),
                        TextInput::make('start_station')
                            ->label('出発駅')
                            ->required()
                            ->hidden(! auth()->user()->transportation_expenses_flag),
                        TextInput::make('end_station')
                            ->label('終着駅')
                            ->required()
                            ->hidden(! auth()->user()->transportation_expenses_flag),
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
                        ->view('tables.columns.user-name-switcher')
                        ->searchable(query: function (Builder $query, string $search): Builder {
                            return $query->whereHas('user', function (Builder $subQuery) use ($search) {
                                $subQuery->where('name', 'like', "%{$search}%");
                            });
                        }),
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
                    ViewColumn::make('start_time')
                        ->label('出勤時間')
                        ->searchable()
                        ->toggleable(isToggledHiddenByDefault: false)
                        ->view('tables.columns.start-time-switcher'),
                    ViewColumn::make('end_time')
                        ->label('退勤時間')
                        ->searchable()
                        ->toggleable(isToggledHiddenByDefault: false)
                        ->view('tables.columns.end-time-switcher'),
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
                            // 退勤時刻の保存処理
                            $start_time = Carbon::parse($record->start_time);
                            $end_time = Carbon::parse($record->end_time);

                            // 差分を計算して HH:mm:ss 形式にフォーマット
                            $working_time = $end_time->diff($start_time)->format('%H:%I:%S');

                            // 現在の位置情報を取得する
                            $address = Address::where('user_id', $record['user_id'])->first();

                            $record->update([
                                'end_time' => $end_time,
                                'end_address' => $address->now_address,
                                'working_time' => $working_time,
                            ]);
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
