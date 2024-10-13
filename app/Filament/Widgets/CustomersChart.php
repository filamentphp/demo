<?php

namespace App\Filament\Widgets;

use App\Models\Shop\Customer;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class CustomersChart extends ChartWidget
{
    protected static ?string $heading = 'Total customers';

    protected static ?int $sort = 2;

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        return $this->getMonthlyCustomers();
    }

    public function getMonthlyCustomers()
    {
        $monthlyCustomers = Customer::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month')
            ->toArray();

        $customerData = [];
        for ($i = 1; $i <= 12; $i++) {
            $customerData[] = $monthlyCustomers[$i]['total'] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Customers',
                    'data' => $customerData,
                    'fill' => 'start',
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        ];
    }

}
