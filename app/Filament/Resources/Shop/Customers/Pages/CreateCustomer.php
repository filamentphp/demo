<?php

namespace App\Filament\Resources\Shop\Customers\Pages;

use App\Filament\Resources\Shop\Customers\CustomerResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomer extends CreateRecord
{
    protected static string $resource = CustomerResource::class;
}
