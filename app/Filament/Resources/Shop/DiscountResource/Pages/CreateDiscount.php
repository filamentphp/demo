<?php

declare(strict_types=1);

namespace App\Filament\Resources\Shop\DiscountResource\Pages;

use App\Filament\Resources\Shop\DiscountResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateDiscount extends CreateRecord
{
    protected static string $resource = DiscountResource::class;
}
