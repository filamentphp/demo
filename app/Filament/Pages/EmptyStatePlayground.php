<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\EmptyStateBlade;
use App\Filament\Widgets\EmptyStateReact;
use App\Filament\Widgets\EmptyStateSvelte;
use App\Filament\Widgets\EmptyStateVue;
use Filament\Pages\Dashboard;

class EmptyStatePlayground extends Dashboard
{
    protected static string $routePath = 'empty-state-playground';

    protected static ?string $title = 'Empty state playground';

    public function getWidgets(): array
    {
        return [EmptyStateBlade::class, EmptyStateReact::class, EmptyStateVue::class, EmptyStateSvelte::class];
    }
}
