<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BasePage;
use App\Filament\Widgets\StatsOverviewWidget;
use App\Filament\Widgets\LiveMoney;
use App\Filament\Widgets\CustomersCharts;

class Dashbaord extends BasePage
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.dashbaord';

    public function getHeading(): string
    {
        return "Tableau de bord";
    }

    public function getHeaderWidgets(): array
    {
        return [
            LiveMoney::class,
            CustomersCharts::class,
        ];
    }
}
