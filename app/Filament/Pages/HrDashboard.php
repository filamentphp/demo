<?php

namespace App\Filament\Pages;

use App\Filament\DemoIconAlias;
use App\Filament\Widgets\BudgetBurnRateChart;
use App\Filament\Widgets\DepartmentLeaveLoadChart;
use App\Filament\Widgets\ProjectHealthChart;
use App\Filament\Widgets\UtilizationRateChart;
use App\Filament\Widgets\WorkforceInsightsStats;
use BackedEnum;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Support\Facades\FilamentIcon;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

class HrDashboard extends BaseDashboard
{
    protected static string $routePath = 'hr';

    protected static ?string $title = 'HR Dashboard';

    protected static ?int $navigationSort = 3;

    public static function getNavigationIcon(): string | BackedEnum | Htmlable | null
    {
        return FilamentIcon::resolve(DemoIconAlias::PAGES_HR_DASHBOARD_NAVIGATION) ?? Heroicon::OutlinedBriefcase;
    }

    public function getWidgets(): array
    {
        return [
            WorkforceInsightsStats::class,
            DepartmentLeaveLoadChart::class,
            ProjectHealthChart::class,
            UtilizationRateChart::class,
            BudgetBurnRateChart::class,
        ];
    }
}
