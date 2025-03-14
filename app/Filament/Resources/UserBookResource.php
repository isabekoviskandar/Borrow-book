<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserBookResource\Pages;
use App\Models\UserBook;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Radio;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Illuminate\Database\Eloquent\Builder;

class UserBookResource extends Resource
{
    protected static ?string $model = UserBook::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),

                Select::make('book_id')
                    ->relationship('book', 'name')
                    ->required(),

                TextInput::make('count')
                    ->numeric()
                    ->minValue(1)
                    ->required(),

                TextInput::make('total')
                    ->numeric()
                    ->required(),

                Radio::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'borrowed' => 'Borrowed',
                        'returned' => 'Returned',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')->label('User')->sortable()->searchable(),
                TextColumn::make('book.name')->label('Book')->sortable()->searchable(),
                TextColumn::make('count')->sortable(),
                TextColumn::make('total')->sortable(),
                BadgeColumn::make('status')
                    ->colors([
                        'pending' => 'warning',
                        'borrowed' => 'info',
                        'returned' => 'success',
                    ])
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListUserBooks::route('/'),
            'create' => Pages\CreateUserBook::route('/create'),
            'edit' => Pages\EditUserBook::route('/{record}/edit'),
        ];
    }
}
