<?php

namespace App\Filament\Pages;

class SvelteInertiaWorkbench extends InertiaWorkbench
{
    protected static ?string $title = 'Inertia Svelte';

    protected static string $inertiaComponent = 'SvelteWorkbench';

    protected static string $rendererModule = 'resources/js/inertia-svelte.js';

    protected static string $framework = 'Svelte';
}
