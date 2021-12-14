<?php

declare(strict_types=1);

namespace App\Filament\Resources\Shop\DiscountResource\Pages;

use App\Filament\Resources\Shop\DiscountResource;
use Filament\Resources\Pages\EditRecord;

final class EditDiscount extends EditRecord
{
    protected static string $resource = DiscountResource::class;

    protected function beforeFill(): void
    {
        $this->record['usage_limit_enabled'] = $this->record['usage_limit'] !== '';
    }
}
