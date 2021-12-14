<?php

declare(strict_types=1);

namespace App\Filament\Resources\Shop\ReviewResource\Pages;

use App\Filament\Resources\Shop\ReviewResource;
use Filament\Resources\Pages\EditRecord;

final class EditReview extends EditRecord
{
    protected static string $resource = ReviewResource::class;
}
