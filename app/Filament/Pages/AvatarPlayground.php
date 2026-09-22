<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\AvatarBlade;
use App\Filament\Widgets\AvatarReact;
use App\Filament\Widgets\AvatarSvelte;
use App\Filament\Widgets\AvatarVue;
use Filament\Pages\Dashboard;

class AvatarPlayground extends Dashboard
{
    protected static string $routePath = 'avatar-playground';

    protected static ?string $title = 'Avatar playground';

    public function getWidgets(): array
    {
        return [AvatarBlade::class, AvatarReact::class, AvatarVue::class, AvatarSvelte::class];
    }
}
