<?php

namespace App\Filament\Teams\Resources\ProductResource\Pages;

use App\Filament\Teams\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;
}
