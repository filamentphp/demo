<?php

namespace App\Filament\Clusters\Products\Resources\Brands\Pages;

use App\Filament\Clusters\Products\Resources\Brands\BrandResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBrand extends CreateRecord
{
    protected static string $resource = BrandResource::class;
}
