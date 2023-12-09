<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MonthlyReportResource\Pages;
use App\Filament\Resources\MonthlyReportResource\RelationManagers;
use App\Models\MonthlyReport;
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

class MonthlyReportResource extends Resource
{
    protected static ?string $model = MonthlyReport::class;
    // ページタイトル
    protected static ?string $pluralModelLabel = '月報情報';
    // ページアイコン
    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    // ナビゲーションの名前
    protected static ?string $navigationLabel = '月報管理';
    // ナビゲーションの並び替え
    protected static ?int $navigationSort = 3;

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
                    TextColumn::make('date')
                        ->label('月間')
                        ->searchable()
                        ->toggleable(isToggledHiddenByDefault: false)
                        ->dateTime('Y年m月'),
                    ViewColumn::make('total_working_time')
                        ->label('月間勤務時間')
                        ->searchable()
                        ->view('tables.columns.woking-time-switcher')
                        ->toggleable(isToggledHiddenByDefault: false),
                    ViewColumn::make('total_over_time')
                        ->label('月間残業時間')
                        ->searchable()
                        ->view('tables.columns.over-time-switcher')
                        ->toggleable(isToggledHiddenByDefault: false),
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
            'index' => Pages\ListMonthlyReports::route('/'),
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
