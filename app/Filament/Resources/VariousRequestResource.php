<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VariousRequestResource\Pages;
use App\Filament\Resources\VariousRequestResource\RelationManagers;
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
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

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
                            ->options(config('services.type')),
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
                        ->view('tables.columns.user-name-switcher'),
                    TextColumn::make('result')
                        ->label('申請日')
                        ->searchable()
                        ->toggleable(isToggledHiddenByDefault: false)
                        ->dateTime('Y年m月d日'),
                    ViewColumn::make('type')
                        ->label('申請種別')
                        ->toggleable(isToggledHiddenByDefault: false)
                        ->view('tables.columns.type-switcher'),
                    TextColumn::make('correction_start_time')
                        ->label('修正出勤時間')
                        ->toggleable(isToggledHiddenByDefault: false)
                        ->dateTime('G時i分'),
                    TextColumn::make('correction_end_time')
                        ->label('修正退勤時間')
                        ->toggleable(isToggledHiddenByDefault: false)
                        ->dateTime('G時i分'),
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
                            // あとで結果に応じた表示の制御を追加
                            $ret = true;
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
                            // あとで結果に応じた表示の制御を追加
                            $ret = true;
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
                            // あとで結果に応じた表示の制御を追加
                            $ret = true;
                            return $ret;
                        }
                    ),

                // 管理者権限のみ表示するアクション
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

                        }
                    )
                    ->visible(
                        function ($record) :bool{
                            $ret = false;
                            // あとで承認か却下実施後は非表示になるように制御する　監理者のみ表示の制御も追加する
                            $ret = true;
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
                            // 却下の処理を個々に記述

                        }
                    )
                    ->visible(
                        function ($record) :bool{
                            $ret = false;
                            // あとで承認か却下実施後は非表示になるように制御する　監理者のみ表示の制御も追加する
                            $ret = true;
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
}