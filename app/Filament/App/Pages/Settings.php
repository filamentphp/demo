<?php

namespace App\Filament\App\Pages;

use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class Settings extends Page
{
    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected string $view = 'filament.app.pages.settings';
}
