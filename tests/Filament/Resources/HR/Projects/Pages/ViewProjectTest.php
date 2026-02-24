<?php

use App\Filament\Resources\HR\Projects\Pages\ViewProject;
use App\Models\HR\Project;
use Livewire\Livewire;

it('can render the view page', function () {
    $record = Project::factory()->create();

    Livewire::test(ViewProject::class, ['record' => $record->getRouteKey()])
        ->assertOk();
});
