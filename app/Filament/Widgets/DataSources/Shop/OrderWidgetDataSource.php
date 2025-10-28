<?php

namespace App\Filament\Widgets\DataSources\Shop;

use App\Models\Shop\Brand;
use App\Models\Shop\Order;
use Filament\CustomDashboardsPlugin\Widgets\DataSources\Attributes\Attribute;
use Filament\CustomDashboardsPlugin\Widgets\DataSources\Attributes\DateAttribute;
use Filament\CustomDashboardsPlugin\Widgets\DataSources\Attributes\NumericAttribute;
use Filament\CustomDashboardsPlugin\Widgets\DataSources\EloquentWidgetDataSource;

class OrderWidgetDataSource extends EloquentWidgetDataSource
{
    protected ?string $model = Order::class;

    /**
     * @return array<Attribute>
     */
    public function getAttributes(): array
    {
        return [
            NumericAttribute::make('total_price')
                ->money(),
            NumericAttribute::make('shipping_price')
                ->money(),
            DateAttribute::make('created_at')
                ->label('Created date'),
            DateAttribute::make('updated_at')
                ->label('Last updated date'),
        ];
    }
}
