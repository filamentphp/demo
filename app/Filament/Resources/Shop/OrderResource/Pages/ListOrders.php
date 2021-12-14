<?php

declare(strict_types=1);

namespace App\Filament\Resources\Shop\OrderResource\Pages;

use App\Filament\Resources\Shop\OrderResource;
use Closure;
use Filament\Resources\Pages\ListRecords;

final class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getTableRecordUrlUsing(): ?Closure
    {
        return null;
    }
}
