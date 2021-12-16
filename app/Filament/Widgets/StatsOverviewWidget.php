<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 0;

    protected function getCards(): array
    {
        return [
            Card::make('Revenue', '$192.1k')
                ->description('32k increase')
                ->descriptionColor('success')
                ->descriptionIcon('heroicon-s-trending-up'),
            Card::make('New orders', '3543')
                ->description('7% increase')
                ->descriptionColor('danger')
                ->descriptionIcon('heroicon-s-trending-down'),
            Card::make('New customers', '1340')
                ->description('3% increase')
                ->descriptionColor('success')
                ->descriptionIcon('heroicon-s-trending-up'),
        ];
    }
}
