<?php

declare(strict_types=1);

namespace App\Filament\Resources\Shop\BrandResource\Pages;

use App\Filament\Resources\Shop\BrandResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateBrand extends CreateRecord
{
    protected static string $resource = BrandResource::class;
}
