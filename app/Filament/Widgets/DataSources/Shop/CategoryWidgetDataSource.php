<?php

namespace App\Filament\Widgets\DataSources\Shop;

use App\Models\Shop\Category;
use Filament\CustomDashboardsPlugin\Enums\DateUnit;
use Filament\CustomDashboardsPlugin\Widgets\DataSources\Attributes\Attribute;
use Filament\CustomDashboardsPlugin\Widgets\DataSources\Attributes\DateTimeAttribute;
use Filament\CustomDashboardsPlugin\Widgets\DataSources\EloquentWidgetDataSource;

class CategoryWidgetDataSource extends EloquentWidgetDataSource
{
    protected ?string $model = Category::class;

    /**
     * @return array<Attribute>
     */
    public function getAttributes(): array
    {
        return [
            DateTimeAttribute::make('created_at')
                ->label('Created date'),
            DateTimeAttribute::make('updated_at')
                ->label('Last updated date'),
        ];
    }
}
