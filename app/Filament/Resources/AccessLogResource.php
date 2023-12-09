<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AccessLogResource\Pages;
use App\Filament\Resources\AccessLogResource\RelationManagers;
use App\Models\AccessLog;
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

class AccessLogResource extends Resource
{
    protected static ?string $model = AccessLog::class;

    // ページタイトル
    protected static ?string $pluralModelLabel = 'アクセスログ'; 

    // アイコン
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-list';

    // ナビゲーションの名前
    protected static ?string $navigationLabel = 'アクセスログ'; 

    // ナビゲーション並び替え
    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(1)
                    ->schema([
                        Textarea::make('access_url')
                            ->label('アクセスURL'),
                        ]),
                Grid::make(4)
                    ->schema([
                        TextInput::make('kubun')
                            ->label('メソッド'),
                        ]),
                Grid::make(1)
                    ->schema([
                        Textarea::make('form_value')
                            ->label('フォーム値'),
                        ]),
                Grid::make(3)
                    ->schema([
                        DateTimePicker::make('created_at')
                                ->label('作成日')
                                ->displayFormat('Y/m/d H:i:s'),
                        ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Split::make([
                    TextColumn::make('access_url')
                        ->label('アクセスURL')
                        ->sortable()
                        ->searchable()
                        ->limit(30)
                        ->toggleable(isToggledHiddenByDefault: false),
                    TextColumn::make('kubun')
                        ->label('メソッド')
                        ->sortable()
                        ->searchable()
                        ->toggleable(isToggledHiddenByDefault: false),
                    TextColumn::make('form_value')
                        ->label('フォーム値')
                        ->sortable()
                        ->searchable()
                        ->limit(30)
                        ->toggleable(isToggledHiddenByDefault: false),
                    TextColumn::make('created_at')
                        ->label('作成日')
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
            'index' => Pages\ListAccessLogs::route('/'),
            'view' => Pages\ViewAccessLog::route('/{record}'),
        ];
    }    
}