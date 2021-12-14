<?php

declare(strict_types=1);

namespace App\Filament\Resources\Shop\DiscountResource\Pages;

use App\Filament\Resources\Shop\DiscountResource;
use Closure;
use Filament\Resources\Pages\ListRecords;

final class ListDiscounts extends ListRecords
{
    protected static string $resource = DiscountResource::class;

    protected function getTableRecordUrlUsing(): ?Closure
    {
        return null;
    }
}
