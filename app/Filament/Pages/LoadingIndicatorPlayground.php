<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\LoadingIndicatorBlade;
use App\Filament\Widgets\LoadingIndicatorReact;
use App\Filament\Widgets\LoadingIndicatorSvelte;
use App\Filament\Widgets\LoadingIndicatorVue;
use Filament\Pages\Dashboard;

class LoadingIndicatorPlayground extends Dashboard
{
    protected static string $routePath = 'loading-indicator-playground';

    protected static ?string $title = 'Loading indicator playground';

    public function getWidgets(): array
    {
        return [LoadingIndicatorBlade::class, LoadingIndicatorReact::class, LoadingIndicatorVue::class, LoadingIndicatorSvelte::class];
    }
}
