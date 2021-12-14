<?php

declare(strict_types=1);

namespace App\Filament\Resources\Shop\BrandResource\Pages;

use App\Filament\Resources\Shop\BrandResource;
use Closure;
use Filament\Resources\Pages\ListRecords;

final class ListBrands extends ListRecords
{
    protected static string $resource = BrandResource::class;

    protected function getTableRecordUrlUsing(): ?Closure
    {
        return null;
    }
}
