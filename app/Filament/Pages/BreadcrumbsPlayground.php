<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\BreadcrumbsBlade;
use App\Filament\Widgets\BreadcrumbsReact;
use App\Filament\Widgets\BreadcrumbsSvelte;
use App\Filament\Widgets\BreadcrumbsVue;
use Filament\Pages\Dashboard;

class BreadcrumbsPlayground extends Dashboard
{
    protected static string $routePath = 'breadcrumbs-playground';

    protected static ?string $title = 'Breadcrumbs playground';

    public function getWidgets(): array
    {
        return [BreadcrumbsBlade::class, BreadcrumbsReact::class, BreadcrumbsVue::class, BreadcrumbsSvelte::class];
    }
}
