<?php

declare(strict_types=1);

namespace App\Filament\Resources\Shop\OrderResource\Pages;

use App\Filament\Resources\Shop\OrderResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $total =  0;
        foreach ($data['items'] as $item) {
            $total += $item['unit_price'] * $item['qty'];
        }

        $data['total_price'] = $total;

        return $data;
    }
}
