<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\ActionsBlade;
use App\Filament\Widgets\ActionsReact;
use App\Filament\Widgets\ActionsSvelte;
use App\Filament\Widgets\ActionsVue;
use Filament\Pages\Dashboard;

class ActionsPlayground extends Dashboard
{
    protected static string $routePath = 'actions-playground';

    protected static ?string $title = 'Actions playground';

    public function getWidgets(): array
    {
        return [ActionsBlade::class, ActionsReact::class, ActionsVue::class, ActionsSvelte::class];
    }
}
