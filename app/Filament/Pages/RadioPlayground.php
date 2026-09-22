<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\RadioBlade;
use App\Filament\Widgets\RadioReact;
use App\Filament\Widgets\RadioSvelte;
use App\Filament\Widgets\RadioVue;
use Filament\Pages\Dashboard;

class RadioPlayground extends Dashboard
{
    protected static string $routePath = 'radio-playground';

    protected static ?string $title = 'Radio playground';

    public function getWidgets(): array
    {
        return [RadioBlade::class, RadioReact::class, RadioVue::class, RadioSvelte::class];
    }
}
