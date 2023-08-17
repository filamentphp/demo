<?php

namespace App\Filament\Widgets;


use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class LiveMoney extends BaseWidget
{
    protected static ?int $sort = 0;

       protected function getStats(): array
       {
            return [
                Stat::make('Unique views', '192.1k'),
                Stat::make('Bounce rate', '21%'),
                Stat::make('Average time on page', '3:12'),
            ];
        }

}
