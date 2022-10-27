<?php

namespace App\Providers\Filament;

use App\Models\User;
use Filament\Context;
use Filament\ContextServiceProvider;

class CommentsContextProvider extends ContextServiceProvider
{
    public function configureContext(Context $context): void
    {
        $context
            ->id('comments')
            ->path('comments')
            ->tenant(User::class)
            ->discoverResources(in: app_path('Filament/Comments/Resources'), for: 'App\\Filament\\Comments\\Resources')
            ->discoverPages(in: app_path('Filament/Comments/Pages'), for: 'App\\Filament\\Comments\\Pages')
            ->discoverWidgets(in: app_path('Filament/Comments/Widgets'), for: 'App\\Filament\\Comments\\Widgets');
    }
}
