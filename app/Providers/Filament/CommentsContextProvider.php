<?php

namespace App\Providers\Filament;

use App\Models\User;
use Filament\Context;
use Filament\ContextProvider;

class CommentsContextProvider extends ContextProvider
{
    public function context(Context $context): Context
    {
        return $context
            ->id('comments')
            ->path('comments')
            ->tenant(User::class)
            ->discoverResources(in: app_path('Filament/Comments/Resources'), for: 'App\\Filament\\Comments\\Resources')
            ->discoverPages(in: app_path('Filament/Comments/Pages'), for: 'App\\Filament\\Comments\\Pages')
            ->discoverWidgets(in: app_path('Filament/Comments/Widgets'), for: 'App\\Filament\\Comments\\Widgets');
    }
}
