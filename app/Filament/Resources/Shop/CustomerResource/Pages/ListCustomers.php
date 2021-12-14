<?php

declare(strict_types=1);

namespace App\Filament\Resources\Shop\CustomerResource\Pages;

use App\Filament\Resources\Shop\CustomerResource;
use Closure;
use Filament\Resources\Pages\ListRecords;

final class ListCustomers extends ListRecords
{
    protected static string $resource = CustomerResource::class;

    protected function getTableRecordUrlUsing(): ?Closure
    {
        return null;
    }
}
