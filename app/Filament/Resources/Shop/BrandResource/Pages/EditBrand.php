<?php

namespace App\Filament\Resources\Shop\BrandResource\Pages;

use App\Filament\Resources\Shop\BrandResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBrand extends EditRecord
{
    protected static string $resource = BrandResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
