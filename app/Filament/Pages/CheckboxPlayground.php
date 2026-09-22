<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\CheckboxBlade;
use App\Filament\Widgets\CheckboxReact;
use App\Filament\Widgets\CheckboxSvelte;
use App\Filament\Widgets\CheckboxVue;
use Filament\Pages\Dashboard;

class CheckboxPlayground extends Dashboard
{
    protected static string $routePath = 'checkbox-playground';

    protected static ?string $title = 'Checkbox playground';

    public function getWidgets(): array
    {
        return [CheckboxBlade::class, CheckboxReact::class, CheckboxVue::class, CheckboxSvelte::class];
    }
}
