<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\CalloutBlade;
use App\Filament\Widgets\CalloutReact;
use App\Filament\Widgets\CalloutSvelte;
use App\Filament\Widgets\CalloutVue;
use Filament\Pages\Dashboard;

class CalloutPlayground extends Dashboard
{
    protected static string $routePath = 'callout-playground';

    protected static ?string $title = 'Callout playground';

    public function getWidgets(): array
    {
        return [CalloutBlade::class, CalloutReact::class, CalloutVue::class, CalloutSvelte::class];
    }
}
