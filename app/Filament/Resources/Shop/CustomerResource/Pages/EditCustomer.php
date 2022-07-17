<?php

namespace App\Filament\Resources\Shop\CustomerResource\Pages;

use App\Filament\Resources\Shop\CustomerResource;
use Filament\Pages;
use Filament\Resources\Pages\EditRecord;

class EditCustomer extends EditRecord
{
    protected static string $resource = CustomerResource::class;

    protected function getActions(): array
    {
        return [
            Pages\Actions\DeleteAction::make(),
            Pages\Actions\RestoreAction::make(),
            Pages\Actions\ForceDeleteAction::make(),
        ];
    }
}
