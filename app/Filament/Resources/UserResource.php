<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    // ページタイトル
    protected static ?string $pluralModelLabel = 'アカウント情報';
    // ページアイコン
    protected static ?string $navigationIcon = 'heroicon-o-user';
    // ナビゲーションの名前
    protected static ?string $navigationLabel = 'アカウント情報管理';
    // ナビゲーションの並び替え
    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(1)
                    ->schema([
                        TextInput::make('name')
                            ->label('氏名')
                            ->placeholder('フルネームで入力')
                            ->required(),
                        TextInput::make('name_kana')
                            ->label('ふりがな')
                            ->placeholder('フルネームでひらがなで入力'),
                        TextInput::make('email')
                            ->label('メールアドレス')
                            ->email()
                            ->required(),
                        TextInput::make('password')
                            ->label('パスワード')
                            ->password()
                            ->required(),
                        DatePicker::make('joining_date')
                            ->label('入社日')
                            ->displayFormat('Y/m/d')
                            ->disabled(! auth()->user()->authority),
                        DatePicker::make('ritirement_date')
                            ->label('退職日')
                            ->displayFormat('Y/m/d')
                            ->disabled(! auth()->user()->authority),
                        
                    ]),
                Toggle::make('authority')
                    ->required()
                    ->label('管理者権限')
                    ->hidden(! auth()->user()->authority)
                    ->disabled(! auth()->user()->authority),
                Toggle::make('transportation_expenses_flag')
                    ->required()
                    ->label('交通費フラグ')
                    ->disabled(! auth()->user()->authority),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Split::make([
                    TextColumn::make('name')
                        ->label('氏名')
                        ->sortable()
                        ->searchable()
                        ->toggleable(isToggledHiddenByDefault: false),
                    TextColumn::make('email')
                        ->label('メールアドレス')
                        ->sortable()
                        ->searchable()
                        ->toggleable(isToggledHiddenByDefault: false),
                    TextColumn::make('joining_date')
                        ->label('入社日')
                        ->sortable()
                        ->searchable()
                        ->toggleable(isToggledHiddenByDefault: false)
                        ->dateTime('Y年m月d日'),
                    TextColumn::make('updated_at')
                        ->label('更新日')
                        ->searchable()
                        ->toggleable(isToggledHiddenByDefault: false)
                        ->dateTime('Y年m月d日'),
                ])->from('md')
            ])
            ->filters([
                //
            ])
            ->actions([
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
            'index' => Pages\ListUsers::route('/'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }    

    public static function getEloquentQuery(): Builder
    {
        // 権限がない場合の処理
        if (auth()->user()->authority !== 1) {
            return parent::getEloquentQuery()
                ->where('id', Auth::id());
        // 権限がある場合の処理
        } else {
            return parent::getEloquentQuery();
        }
    }

}
