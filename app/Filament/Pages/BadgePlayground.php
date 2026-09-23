<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\BadgeBlade;
use App\Filament\Widgets\BadgeReact;
use App\Filament\Widgets\BadgeSvelte;
use App\Filament\Widgets\BadgeVue;
use Filament\Pages\Dashboard;

class BadgePlayground extends Dashboard
{
    protected static string $routePath = 'badge-playground';

    protected static ?string $title = 'Badge playground';

    public function getWidgets(): array
    {
        return [BadgeBlade::class, BadgeReact::class, BadgeVue::class, BadgeSvelte::class];
    }
}
