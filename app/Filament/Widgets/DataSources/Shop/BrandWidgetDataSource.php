<?php

namespace App\Filament\Widgets\DataSources\Shop;

use App\Models\Shop\Brand;
use App\Models\Shop\Category;
use Filament\CustomDashboardsPlugin\Widgets\DataSources\Attributes\Attribute;
use Filament\CustomDashboardsPlugin\Widgets\DataSources\Attributes\DateAttribute;
use Filament\CustomDashboardsPlugin\Widgets\DataSources\Attributes\NumberAttribute;
use Filament\CustomDashboardsPlugin\Widgets\DataSources\EloquentWidgetDataSource;

class BrandWidgetDataSource extends EloquentWidgetDataSource
{
    protected ?string $model = Brand::class;

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
        ];
    }
}
