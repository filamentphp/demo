<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\FieldsetBlade;
use App\Filament\Widgets\FieldsetReact;
use App\Filament\Widgets\FieldsetSvelte;
use App\Filament\Widgets\FieldsetVue;
use Filament\Pages\Dashboard;

class FieldsetPlayground extends Dashboard
{
    protected static string $routePath = 'fieldset-playground';

    protected static ?string $title = 'Fieldset playground';

    public function getWidgets(): array
    {
        return [FieldsetBlade::class, FieldsetReact::class, FieldsetVue::class, FieldsetSvelte::class];
    }
}
