<?php

namespace App\LiveDemo;

use Filament\Panel;
use Filament\View\PanelsRenderHook;

final class Theme
{
    public static function configure(Panel $panel): Panel
    {
        return PreviewPanel::make()
            ->topbar(false)
            ->renderHook(PanelsRenderHook::BODY_END, fn () => view('live-demo.toolbar'));
    }
}
