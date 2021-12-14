<?php

declare(strict_types=1);

namespace App\Filament\Resources\Shop\ReviewResource\Pages;

use App\Filament\Resources\Shop\ReviewResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateReview extends CreateRecord
{
    protected static string $resource = ReviewResource::class;
}
