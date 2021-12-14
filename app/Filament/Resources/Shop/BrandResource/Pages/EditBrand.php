<?php

declare(strict_types=1);

namespace App\Filament\Resources\Shop\BrandResource\Pages;

use App\Filament\Resources\Shop\BrandResource;
use Filament\Resources\Pages\EditRecord;

final class EditBrand extends EditRecord
{
    protected static string $resource = BrandResource::class;
}
