<?php

declare(strict_types=1);

namespace App\Filament\Resources\Shop\OrderResource\Pages;

use App\Filament\Resources\Shop\OrderResource;
use Filament\Resources\Pages\EditRecord;

final class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $total =  0;
        foreach ($data['items'] as $item) {
            $total += $item['unit_price'] * $item['qty'];
        }

        $data['total_price'] = $total;

        return $data;
    }
}
