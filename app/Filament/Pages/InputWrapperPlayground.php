<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\InputWrapperBlade;
use App\Filament\Widgets\InputWrapperReact;
use App\Filament\Widgets\InputWrapperSvelte;
use App\Filament\Widgets\InputWrapperVue;
use Filament\Pages\Dashboard;

class InputWrapperPlayground extends Dashboard
{
    protected static string $routePath = 'input-wrapper-playground';

    protected static ?string $title = 'Input wrapper playground';

    public function getWidgets(): array
    {
        return [InputWrapperBlade::class, InputWrapperReact::class, InputWrapperVue::class, InputWrapperSvelte::class];
    }
}
