<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\LoadingSectionBlade;
use App\Filament\Widgets\LoadingSectionReact;
use App\Filament\Widgets\LoadingSectionSvelte;
use App\Filament\Widgets\LoadingSectionVue;
use Filament\Pages\Dashboard;

class LoadingSectionPlayground extends Dashboard
{
    protected static string $routePath = 'loading-section-playground';

    protected static ?string $title = 'Loading section playground';

    public function getWidgets(): array
    {
        return [LoadingSectionBlade::class, LoadingSectionReact::class, LoadingSectionVue::class, LoadingSectionSvelte::class];
    }
}
