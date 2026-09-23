<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\InputBlade;
use App\Filament\Widgets\InputReact;
use App\Filament\Widgets\InputSvelte;
use App\Filament\Widgets\InputVue;
use Filament\Pages\Dashboard;

class InputPlayground extends Dashboard
{
    protected static string $routePath = 'input-playground';

    protected static ?string $title = 'Input playground';

    public function getWidgets(): array
    {
        return [InputBlade::class, InputReact::class, InputVue::class, InputSvelte::class];
    }
}
