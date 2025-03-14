<?php

namespace App\Filament\Resources\UserBookResource\Pages;

use App\Filament\Resources\UserBookResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUserBook extends CreateRecord
{
    protected static string $resource = UserBookResource::class;
}
