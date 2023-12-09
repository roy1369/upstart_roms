<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BatchLogResource\Pages;
use App\Filament\Resources\BatchLogResource\RelationManagers;
use App\Models\BatchLog;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BatchLogResource extends Resource
{
    protected static ?string $model = BatchLog::class;

    // ページタイトル
    protected static ?string $pluralModelLabel = 'バッチログ'; 

    // アイコン
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-list'; 

    // ナビゲーションの名前
    protected static ?string $navigationLabel = 'バッチログ'; 

    // ナビゲーション並び替え
    protected static ?int $navigationSort = 7;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make()
                    ->schema([
                        TextInput::make('batch_name')
                            ->label('バッチ名'),
                        TextInput::make('ending_kubun')
                            ->label('終了区分'),
                        DateTimePicker::make('start_date_and_time')
                            ->label('開始日時')
                            ->displayFormat('Y/m/d H:i:s'),
                        DateTimePicker::make('ending_date_and_time')
                            ->label('終了日時')
                            ->displayFormat('Y/m/d H:i:s'),
                    ]),
                Grid::make(1)
                    ->schema([
                        TextInput::make('message')
                            ->label('メッセージ'),
                        Textarea::make('error_stack_trace')
                            ->label('エラースタックトレース'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Split::make([
                    TextColumn::make('batch_name')
                        ->label('バッチ名')
                        ->sortable()
                        ->searchable()
                        ->toggleable(isToggledHiddenByDefault: false),
                    TextColumn::make('ending_kubun')
                        ->label('終了区分')
                        ->sortable()
                        ->searchable()
                        ->toggleable(isToggledHiddenByDefault: false),
                    TextColumn::make('start_date_and_time')
                        ->label('開始日時')
                        ->sortable()
                        ->searchable()
                        ->toggleable(isToggledHiddenByDefault: false)
                        ->dateTime('Y/m/d H:i:s'),
                    TextColumn::make('ending_date_and_time')
                        ->label('終了日時')
                        ->sortable()
                        ->searchable()
                        ->toggleable(isToggledHiddenByDefault: false)
                        ->dateTime('Y/m/d H:i:s'),
                ])->from('md')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('詳細')
                    ->icon('')
                    ->button(),
            ])
            ->bulkActions([
                // 
            ]);
    }
    
    public static function getRelations(): array
    {
        return [];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBatchLogs::route('/'),
            'view' => Pages\ViewBatchLog::route('/{record}'),
        ];
    }    
}