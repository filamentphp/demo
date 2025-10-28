<?php

namespace App\Filament\Widgets\DataSources\Shop;

use App\Models\Shop\Order;
use App\Models\Shop\Product;
use Filament\CustomDashboardsPlugin\Widgets\DataSources\Attributes\Attribute;
use Filament\CustomDashboardsPlugin\Widgets\DataSources\Attributes\DateAttribute;
use Filament\CustomDashboardsPlugin\Widgets\DataSources\Attributes\NumericAttribute;
use Filament\CustomDashboardsPlugin\Widgets\DataSources\EloquentWidgetDataSource;
use Filament\QueryBuilder\Constraints\RelationshipConstraint;
use Filament\QueryBuilder\Constraints\RelationshipConstraint\Operators\IsRelatedToOperator;
use Filament\Tables\Filters\QueryBuilder\Constraints\BooleanConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\DateConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\NumberConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\TextConstraint;

class ProductWidgetDataSource extends EloquentWidgetDataSource
{
    protected ?string $model = Product::class;

    /**
     * @return array<Attribute>
     */
    public function getAttributes(): array
    {
        return [
            NumericAttribute::make('qty')
                ->label('Quantity')
                ->decimalPlaces(0),
            NumericAttribute::make('security_stock')
                ->decimalPlaces(0),
            NumericAttribute::make('price')
                ->money(),
            NumericAttribute::make('cost')
                ->money(),
            DateAttribute::make('created_at')
                ->label('Created date'),
            DateAttribute::make('updated_at')
                ->label('Last updated date'),
        ];
    }

    public function getQueryBuilderConstraints(): array
    {
        return [
            TextConstraint::make('name'),
            TextConstraint::make('slug'),
            TextConstraint::make('sku')
                ->label('SKU (Stock Keeping Unit)'),
            TextConstraint::make('barcode')
                ->label('Barcode (ISBN, UPC, GTIN, etc.)'),
            TextConstraint::make('description'),
            NumberConstraint::make('old_price')
                ->label('Compare at price')
                ->icon('heroicon-m-currency-dollar'),
            NumberConstraint::make('price')
                ->icon('heroicon-m-currency-dollar'),
            NumberConstraint::make('cost')
                ->label('Cost per item')
                ->icon('heroicon-m-currency-dollar'),
            NumberConstraint::make('qty')
                ->label('Quantity'),
            NumberConstraint::make('security_stock'),
            BooleanConstraint::make('is_visible')
                ->label('Visibility'),
            BooleanConstraint::make('featured'),
            BooleanConstraint::make('backorder'),
            BooleanConstraint::make('requires_shipping')
                ->icon('heroicon-m-truck'),
            DateConstraint::make('published_at')
                ->label('Publishing date'),
            RelationshipConstraint::make('brand')
                ->selectable(
                    IsRelatedToOperator::make()
                        ->titleAttribute('name')
                        ->searchable()
                        ->multiple(),
                )
                ->emptyable(),
        ];
    }

    public function getQueryBuilderConstraintPickerColumns(): array | int
    {
        return 2;
    }
}
