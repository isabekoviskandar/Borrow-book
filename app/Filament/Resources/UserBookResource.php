<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserBookResource\Pages;
use App\Models\UserBook;
use App\Models\User;
use App\Models\Book;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;

class UserBookResource extends Resource
{
    protected static ?string $model = UserBook::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationLabel = 'User Books';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->label('User')
                    ->options(User::all()->pluck('name', 'id'))
                    ->searchable()
                    ->required(),

                Select::make('book_id')
                    ->label('Book')
                    ->options(Book::all()->pluck('name', 'id'))
                    ->searchable()
                    ->required(),

                TextInput::make('count')
                    ->label('Quantity')
                    ->numeric()
                    ->minValue(1)
                    ->required(),

                TextInput::make('total')
                    ->label('Total Price')
                    ->numeric()
                    ->prefix('$')
                    ->minValue(0)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->sortable(),

                TextColumn::make('user_book.name')
                    ->label('User')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('book_user.name')
                    ->label('Book')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('count')
                    ->label('Quantity')
                    ->sortable(),

                TextColumn::make('total')
                    ->label('Total Price')
                    ->money('USD')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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