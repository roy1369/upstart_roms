<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaidHolidayResource\Pages;
use App\Filament\Resources\PaidHolidayResource\RelationManagers;
use App\Models\PaidHoliday;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class PaidHolidayResource extends Resource
{
    protected static ?string $model = PaidHoliday::class;
    // ページタイトル
    protected static ?string $pluralModelLabel = '有給情報';
    // ページアイコン
    protected static ?string $navigationIcon = 'heroicon-o-sparkles';
    // ナビゲーションの名前
    protected static ?string $navigationLabel = '有給情報管理';
    // ナビゲーションの並び替え
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
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
                    ViewColumn::make('amount')
                        ->label('有給残日数')
                        ->toggleable(isToggledHiddenByDefault: false)
                        ->view('tables.columns.amount-switcher'),
                    ViewColumn::make('next_paid_holiday')
                        ->label('次回有給取得予定日')
                        ->toggleable(isToggledHiddenByDefault: false)
                        ->view('tables.columns.next-amount-switcher'),
                    
                ])->from('md')
            ])
            ->filters([
                //
            ])
            ->actions([
                // 
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
            'index' => Pages\ListPaidHolidays::route('/'),
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
