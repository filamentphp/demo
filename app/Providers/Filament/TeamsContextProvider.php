<?php

namespace App\Providers\Filament;

use App\Filament\Teams\Pages\RegisterTeam;
use App\Models\Team;
use Filament\Context;
use Filament\ContextProvider;

class TeamsContextProvider extends ContextProvider
{
    public function context(Context $context): Context
    {
        return $context
            ->id('teams')
            ->path('teams')
            ->tenant(Team::class)
            ->tenantRegistrationPage(RegisterTeam::class)
            ->discoverResources(in: app_path('Filament/Teams/Resources'), for: 'App\\Filament\\Teams\\Resources')
            ->discoverPages(in: app_path('Filament/Teams/Pages'), for: 'App\\Filament\\Teams\\Pages')
            ->discoverWidgets(in: app_path('Filament/Teams/Widgets'), for: 'App\\Filament\\Teams\\Widgets');
    }
}
