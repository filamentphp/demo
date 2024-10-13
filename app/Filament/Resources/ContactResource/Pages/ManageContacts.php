<?php

namespace App\Filament\Resources\ContactResource\Pages;

use App\Filament\Resources\ContactResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ManageContacts extends ListRecords
{
    protected static string $resource = ContactResource::class;
}
