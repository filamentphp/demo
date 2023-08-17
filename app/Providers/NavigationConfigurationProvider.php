<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Filament\Facades\Filament;
use Filament\Navigation\NavigationItem;
use Illuminate\Support\Facades\Auth;
use App\Filament\Resources\UserResource;

class NavigationConfigurationProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
       Filament::serving(function () {
            Filament::registerNavigationItems([
                NavigationItem::make('Analytics')
                    ->url('https://filament.pirsch.io', shouldOpenInNewTab: true)
                    ->icon('heroicon-o-presentation-chart-line')
                    ->activeIcon('heroicon-s-presentation-chart-line')
                    ->group('Reports')
                    ->sort(3),
                // NavigationItem::make('Statistiques')
                //     ->url(UserResource::getUrl('statistique'), shouldOpenInNewTab: true)
                //     ->icon('heroicon-o-presentation-chart-line')
                //     ->activeIcon('heroicon-s-presentation-chart-line')
                //     ->group('Reports')
                //     ->sort(3),
            ]);
       });
    }
}
