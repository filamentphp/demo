<?php

namespace App\Filament\Resources\Shop\DiscountResource\Pages;

use App\Filament\Resources\Shop\DiscountResource;
use Filament\Resources\Pages\EditRecord;

class EditDiscount extends EditRecord
{
    protected static string $resource = DiscountResource::class;

    protected function beforeFill(): void
    {
        $this->record['usage_limit_enabled'] = $this->record['usage_limit'] !== '';
    }
}
