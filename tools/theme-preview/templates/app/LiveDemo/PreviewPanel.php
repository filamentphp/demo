<?php

namespace App\LiveDemo;

use Filament\FontProviders\BunnyFontProvider;
use Filament\FontProviders\LocalFontProvider;
use Filament\NoirTheme\NoirThemeColor;
use Filament\Panel;
use Filament\SharpTheme\SharpThemeMaterialSymbols;
use Filament\SoftTheme\SoftThemeColor;
use Filament\Support\Colors\Color;
use Illuminate\Session\Middleware\StartSession;

/** Selection is evaluated at render/boot time, never stored on the worker's panel. */
final class PreviewPanel extends Panel
{
    /** Start the session before panel boot registers request-specific colors and icons. */
    public function getMiddleware(): array
    {
        $middleware = [];

        foreach ($this->middleware as $item) {
            $middleware[] = $item;

            if ($item === StartSession::class) {
                $middleware[] = "panel:{$this->getId()}";
            }
        }

        return $middleware;
    }

    public function getViteTheme(): string
    {
        $selection = Selection::current();

        return 'resources/css/live-demo/' . $selection['theme'] . ($selection['compact'] ? '-compact' : '') . '.css';
    }

    public function getColors(): array
    {
        $palette = match (Selection::current()['theme']) {
            'soft' => SoftThemeColor::class,
            'noir' => NoirThemeColor::class,
            default => null,
        };

        return $palette ? [
            'gray' => $palette::Gray,
            'primary' => $palette::Primary,
            'danger' => $palette::Danger,
            'warning' => $palette::Warning,
            'success' => $palette::Success,
            'info' => $palette::Info,
        ] : ['primary' => Color::Blue];
    }

    public function getIcons(): array
    {
        return Selection::current()['theme'] === 'sharp' ? SharpThemeMaterialSymbols::Aliases : [];
    }

    public function getFontFamily(): string
    {
        return $this->hasCustomFontFamily() ? 'Albert Sans' : 'Inter Variable';
    }

    public function hasCustomFontFamily(): bool
    {
        return in_array(Selection::current()['theme'], ['stock', 'soft'], true);
    }

    public function getFontProvider(): string
    {
        return $this->hasCustomFontFamily() ? BunnyFontProvider::class : LocalFontProvider::class;
    }

    public function getSerifFontFamily(): string
    {
        return $this->hasCustomSerifFontFamily() ? 'Lora' : 'ui-serif';
    }

    public function hasCustomSerifFontFamily(): bool
    {
        return Selection::current()['theme'] === 'soft';
    }
}
