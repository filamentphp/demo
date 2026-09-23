<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Concerns\HasJsRenderer;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Vite;

class InputReact extends Widget
{
    use HasJsRenderer;

    public function getRenderer(): string
    {
        return Vite::asset('resources/js/filament/widgets/input-react.jsx');
    }
}
