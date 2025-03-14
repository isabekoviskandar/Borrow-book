<?php

namespace App\Filament\Resources\UserBookResource\Pages;

use App\Filament\Resources\UserBookResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUserBooks extends ListRecords
{
    protected static string $resource = UserBookResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
