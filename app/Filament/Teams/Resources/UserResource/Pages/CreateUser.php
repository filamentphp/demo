<?php

namespace App\Filament\Teams\Resources\UserResource\Pages;

use App\Filament\Teams\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}
