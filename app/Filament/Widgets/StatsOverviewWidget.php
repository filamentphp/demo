<?php

namespace App\Filament\Widgets;

use App\Models\Shop\Customer;
use App\Models\Shop\Order;
use Carbon\Carbon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class StatsOverviewWidget extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 0;

    protected function getStats(): array
    {
        $startDate = !is_null($this->filters['startDate'] ?? null) ?
            Carbon::parse($this->filters['startDate']) :
            now()->startOfYear();

        $endDate = !is_null($this->filters['endDate'] ?? null) ?
            Carbon::parse($this->filters['endDate']) :
            now();

        $isBusinessCustomersOnly = $this->filters['businessCustomersOnly'] ?? null;
        $businessCustomerMultiplier = match (true) {
            boolval($isBusinessCustomersOnly) => 2 / 3,
            blank($isBusinessCustomersOnly) => 1,
            default => 1 / 3,
        };


        $diffInDays = $startDate->diffInDays($endDate);

        $monthlyRevenue = Order::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('MONTH(created_at) as month, SUM(total_price) as total')
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $monthlyCustomers = Customer::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('MONTH(created_at) as month, COUNT(id) as count')
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        $monthlyOrders = Order::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('MONTH(created_at) as month, COUNT(id) as count')
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        // Apply the businessCustomerMultiplier to the fetched data
        $revenue = (int)(array_sum($monthlyRevenue) * $businessCustomerMultiplier);
        $newCustomers = (int)(array_sum($monthlyCustomers) * $businessCustomerMultiplier);
        $newOrders = (int)(array_sum($monthlyOrders) * $businessCustomerMultiplier);

        // Prepare chart data dynamically for each month
        $revenueChartData = [];
        $customersChartData = [];
        $ordersChartData = [];
        for ($month = 1; $month <= 12; $month++) {
            $revenueChartData[] = $monthlyRevenue[$month] ?? 0;
            $customersChartData[] = $monthlyCustomers[$month] ?? 0;
            $ordersChartData[] = $monthlyOrders[$month] ?? 0;
        }

        $formatNumber = function (int $number): string {
            if ($number < 1000) {
                return (string)Number::format($number, 0);
            }

            if ($number < 1000000) {
                return Number::format($number / 1000, 2) . 'k';
            }

            return Number::format($number / 1000000, 2) . 'm';
        };

        return [
            Stat::make('Revenue',  $formatNumber($revenue) . ' MAD' )
                ->description('Revenue in the selected period')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart($revenueChartData)
                ->color('success'),
            Stat::make('New customers', $formatNumber($newCustomers))
                ->description('Customers acquired in the selected period')
//                ->descriptionIcon('heroicon-m-user')
                ->chart($customersChartData)
                ->color('info'),
            Stat::make('New orders', $formatNumber($newOrders))
                ->description('Orders placed in the selected period')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart($ordersChartData)
                ->color('success'),
        ];
    }
}
