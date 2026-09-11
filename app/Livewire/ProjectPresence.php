<?php

namespace App\Livewire;

use App\Models\HR\Project;
use Filament\Facades\Filament;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class ProjectPresence extends Component
{
    #[Locked]
    public Project $record;

    public function boot(): void
    {
        abort_unless(
            auth()->user()?->canAccessPanel(Filament::getPanel('admin')) === true,
            403,
        );

        abort_unless(Project::query()->whereKey($this->record->getKey())->exists(), 404);
    }

    public function render(): View
    {
        return view('livewire.project-presence');
    }
}
