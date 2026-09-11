<?php

namespace App\Filament\Pages;

class ReactInertiaWorkbench extends InertiaWorkbench
{
    protected static ?string $title = 'Inertia React';

    protected static string $inertiaComponent = 'ReactWorkbench';

    protected static string $rendererModule = 'resources/js/inertia-react.jsx';

    protected static string $framework = 'React';
}
