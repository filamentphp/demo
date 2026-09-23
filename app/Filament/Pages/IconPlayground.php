<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\IconBlade;
use App\Filament\Widgets\IconReact;
use App\Filament\Widgets\IconSvelte;
use App\Filament\Widgets\IconVue;
use Filament\Pages\Dashboard;

class IconPlayground extends Dashboard
{
    protected static string $routePath = 'icon-playground';

    protected static ?string $title = 'Icon playground';

    public function getWidgets(): array
    {
        return [IconBlade::class, IconReact::class, IconVue::class, IconSvelte::class];
    }
}
