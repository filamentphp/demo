<?php

namespace App\Filament\Widgets;

use App\Enums\OrderStatus;
use App\Models\Shop\Customer;
use App\Models\Shop\Order;
use App\Models\Shop\OrderItem;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class ShopKpisStats extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 0;

    protected function getStats(): array
    {
        $startDate = filled($this->pageFilters['startDate'] ?? null)
            ? Carbon::parse($this->pageFilters['startDate'])
            : null;
        $endDate = filled($this->pageFilters['endDate'] ?? null)
            ? Carbon::parse($this->pageFilters['endDate'])
            : now();
        $orderStatuses = $this->pageFilters['orderStatuses'] ?? null;

        $orderQuery = Order::query()
            ->when($startDate, fn ($q) => $q->where('created_at', '>=', $startDate))
            ->when($endDate, fn ($q) => $q->where('created_at', '<=', $endDate))
            ->when(filled($orderStatuses), fn ($q) => $q->whereIn('status', $orderStatuses));

        $totalOrders = $orderQuery->count();
        $totalRevenue = (float) $orderQuery->sum('total_price');
        $cancelledOrders = (clone $orderQuery)->where('status', OrderStatus::Cancelled)->count();

        $orderIds = (clone $orderQuery)->pluck('id');
        $totalItems = OrderItem::whereIn('order_id', $orderIds)->count();

        $totalCustomers = Customer::query()
            ->when($startDate, fn ($q) => $q->where('created_at', '>=', $startDate))
            ->when($endDate, fn ($q) => $q->where('created_at', '<=', $endDate))
            ->count();

        $repeatCustomers = Customer::query()
            ->when($startDate, fn ($q) => $q->where('created_at', '>=', $startDate))
            ->when($endDate, fn ($q) => $q->where('created_at', '<=', $endDate))
            ->has('orders', '>=', 2)
            ->count();

        $repeatRate = $totalCustomers > 0
            ? round(($repeatCustomers / $totalCustomers) * 100, 1)
            : 0;
        $avgItemsPerOrder = $totalOrders > 0
            ? round($totalItems / $totalOrders, 1)
            : 0;
        $cancellationRate = $totalOrders > 0
            ? round(($cancelledOrders / $totalOrders) * 100, 1)
            : 0;
        $revenuePerCustomer = $totalCustomers > 0
            ? round($totalRevenue / $totalCustomers, 2)
            : 0;

        // Monthly sparkline data for the last 7 months
        $months = collect(range(6, 0))->map(fn (int $ago) => Carbon::now()->subMonths($ago)->startOfMonth());

        $monthlyOrders = Order::query()
            ->where('created_at', '>=', $months->first())
            ->when(filled($orderStatuses), fn ($q) => $q->whereIn('status', $orderStatuses))
            ->get();

        $monthlyCustomers = Customer::where('created_at', '>=', $months->first())->get();

        $repeatChart = [];
        $avgItemsChart = [];
        $cancellationChart = [];
        $revenueChart = [];

        foreach ($months as $month) {
            $monthKey = $month->format('Y-m');

            $monthOrders = $monthlyOrders->filter(fn (Order $o): bool => $o->created_at?->format('Y-m') === $monthKey);
            $monthOrderCount = $monthOrders->count();
            $monthRevenue = (float) $monthOrders->sum('total_price');

            $monthCustomers = $monthlyCustomers->filter(fn (Customer $c): bool => $c->created_at?->format('Y-m') === $monthKey);
            $monthCustomerCount = $monthCustomers->count();

            $monthRepeatCount = $monthCustomerCount > 0
                ? $monthCustomers->filter(fn (Customer $c): bool => $c->orders()->count() >= 2)->count()
                : 0;
            $repeatChart[] = $monthCustomerCount > 0
                ? round(($monthRepeatCount / $monthCustomerCount) * 100, 1)
                : 0;

            $monthOrderIds = $monthOrders->pluck('id');
            $monthItems = $monthOrderIds->isNotEmpty() ? OrderItem::whereIn('order_id', $monthOrderIds)->count() : 0;
            $avgItemsChart[] = $monthOrderCount > 0 ? round($monthItems / $monthOrderCount, 1) : 0;

            $monthCancelled = $monthOrders->where('status', OrderStatus::Cancelled)->count();
            $cancellationChart[] = $monthOrderCount > 0
                ? round(($monthCancelled / $monthOrderCount) * 100, 1)
                : 0;

            $revenueChart[] = $monthCustomerCount > 0
                ? round($monthRevenue / $monthCustomerCount, 2)
                : 0;
        }

        return [
            Stat::make('Repeat Customer Rate', $repeatRate . '%')
                ->description($repeatCustomers . ' of ' . $totalCustomers . ' customers')
                ->descriptionIcon(Heroicon::ArrowPath)
                ->chart($repeatChart)
                ->color('success'),
            Stat::make('Avg Items / Order', (string) $avgItemsPerOrder)
                ->description($totalItems . ' items, ' . $totalOrders . ' orders')
                ->descriptionIcon(Heroicon::ShoppingCart)
                ->chart($avgItemsChart)
                ->color('info'),
            Stat::make('Cancellation Rate', $cancellationRate . '%')
                ->description($cancelledOrders . ' cancelled orders')
                ->descriptionIcon(Heroicon::XCircle)
                ->chart($cancellationChart)
                ->color($cancellationRate > 10 ? 'danger' : 'warning'),
            Stat::make('Revenue / Customer', '$' . number_format($revenuePerCustomer, 2))
                ->description('$' . number_format($totalRevenue, 0) . ' total revenue')
                ->descriptionIcon(Heroicon::CurrencyDollar)
                ->chart($revenueChart)
                ->color('success'),
        ];
    }
}
