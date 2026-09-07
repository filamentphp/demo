<?php

namespace App\Filament\App\Pages;

use App\Filament\DemoIconAlias;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Facades\FilamentIcon;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

class Settings extends Page
{
    protected string $view = 'filament.app.pages.settings';

    public static function getNavigationIcon(): string | BackedEnum | Htmlable | null
    {
        return FilamentIcon::resolve(DemoIconAlias::APP_PAGES_SETTINGS_NAVIGATION) ?? Heroicon::OutlinedDocumentText;
    }
}
