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
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'view' => Pages\ViewVariousRequest::route('/{record}'),
            'edit' => Pages\EditVariousRequest::route('/{record}/edit'),
        ];
    }    
}
