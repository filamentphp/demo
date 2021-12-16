<?php

namespace App\Filament\Resources\Shop\CustomerResource\Pages;

use App\Filament\Resources\Shop\CustomerResource;
use Filament\Resources\Pages\ListRecords;

class ListCustomers extends ListRecords
{
    protected static string $resource = CustomerResource::class;
}
