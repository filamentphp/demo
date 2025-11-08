<?php

namespace App\Filament\Widgets\DataSources\Shop;

use App\Models\Shop\Customer;
use Filament\CustomDashboardsPlugin\Widgets\DataSources\Attributes\Attribute;
use Filament\CustomDashboardsPlugin\Widgets\DataSources\Attributes\DateAttribute;
use Filament\CustomDashboardsPlugin\Widgets\DataSources\Attributes\DateTimeAttribute;
use Filament\CustomDashboardsPlugin\Widgets\DataSources\EloquentWidgetDataSource;

class CustomerWidgetDataSource extends EloquentWidgetDataSource
{
    protected ?string $model = Customer::class;

    /**
     * @return array<Attribute>
     */
    public function getAttributes(): array
    {
        return [
            DateAttribute::make('created_at')
                ->label('Created date'),
            DateAttribute::make('updated_at')
                ->label('Last updated date'),
            DateAttribute::make('birthday')
                ->time(false),
        ];
    }
}
