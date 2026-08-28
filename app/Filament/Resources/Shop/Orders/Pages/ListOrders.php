<?php

namespace App\Filament\Resources\Shop\Orders\Pages;

use App\Enums\OrderStatus;
use App\Filament\Resources\Shop\Orders\OrderResource;
use App\Models\Shop\Order;
use Filament\Actions\CreateAction;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;

class ListOrders extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = OrderResource::class;

    protected function getActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return OrderResource::getWidgets();
    }

    public function getTabs(): array
    {
        return [
            null => Tab::make('All')
                ->badge(static fn (): int => Order::query()->count())
                ->deferBadge(),
            'new' => Tab::make()
                ->badge(static fn (): int => Order::query()->where('status', OrderStatus::New)->count())
                ->badgeColor(OrderStatus::New->getColor())
                ->deferBadge()
                ->query(fn ($query) => $query->where('status', OrderStatus::New)),
            'processing' => Tab::make()
                ->badge(static fn (): int => Order::query()->where('status', OrderStatus::Processing)->count())
                ->badgeColor(OrderStatus::Processing->getColor())
                ->deferBadge()
                ->query(fn ($query) => $query->where('status', OrderStatus::Processing)),
            'shipped' => Tab::make()
                ->badge(static fn (): int => Order::query()->where('status', OrderStatus::Shipped)->count())
                ->badgeColor(OrderStatus::Shipped->getColor())
                ->deferBadge()
                ->query(fn ($query) => $query->where('status', OrderStatus::Shipped)),
            'delivered' => Tab::make()
                ->badge(static fn (): int => Order::query()->where('status', OrderStatus::Delivered)->count())
                ->badgeColor(OrderStatus::Delivered->getColor())
                ->deferBadge()
                ->query(fn ($query) => $query->where('status', OrderStatus::Delivered)),
            'cancelled' => Tab::make()
                ->badge(static fn (): int => Order::query()->where('status', OrderStatus::Cancelled)->count())
                ->badgeColor(OrderStatus::Cancelled->getColor())
                ->deferBadge()
                ->query(fn ($query) => $query->where('status', OrderStatus::Cancelled)),
        ];
    }
}
