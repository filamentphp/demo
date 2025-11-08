<?php

namespace App\Filament\Widgets\DataSources\Shop;

use App\Models\Shop\Order;
use App\Models\Shop\Payment;
use Filament\CustomDashboardsPlugin\Widgets\DataSources\Attributes\Attribute;
use Filament\CustomDashboardsPlugin\Widgets\DataSources\Attributes\DateAttribute;
use Filament\CustomDashboardsPlugin\Widgets\DataSources\Attributes\DateTimeAttribute;
use Filament\CustomDashboardsPlugin\Widgets\DataSources\Attributes\NumberAttribute;
use Filament\CustomDashboardsPlugin\Widgets\DataSources\EloquentWidgetDataSource;

class PaymentWidgetDataSource extends EloquentWidgetDataSource
{
    protected ?string $model = Payment::class;

    /**
     * @return array<Attribute>
     */
    public function getAttributes(): array
    {
        return [
            NumberAttribute::make('amount')
                ->money(),
            DateAttribute::make('created_at')
                ->label('Created date'),
            DateAttribute::make('updated_at')
                ->label('Last updated date'),
        ];
    }
}
