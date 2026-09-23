<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\SelectBlade;
use App\Filament\Widgets\SelectReact;
use App\Filament\Widgets\SelectSvelte;
use App\Filament\Widgets\SelectVue;
use Filament\Pages\Dashboard;

class SelectPlayground extends Dashboard
{
    protected static string $routePath = 'select-playground';

    protected static ?string $title = 'Select playground';

    public function getWidgets(): array
    {
        return [SelectBlade::class, SelectReact::class, SelectVue::class, SelectSvelte::class];
    }
}
