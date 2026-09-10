<?php

namespace App\Livewire;

use App\Models\HR\Project;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

class ProjectHistory extends Component
{
    #[Locked]
    public ?Project $record = null;

    #[Locked]
    public int $limit = 20;

    public function boot(): void
    {
        abort_unless(auth()->check(), 403);
    }

    /** @param array{projectId: int} $event */
    #[On('echo-private:projects,ProjectChanged')]
    public function projectChanged(array $event): void
    {
        if ((int) $event['projectId'] !== $this->record?->id) {
            $this->skipRender();
        }
    }

    public function loadMore(): void
    {
        $this->limit += 20;
    }

    public function render(): View
    {
        $activities = $this->record?->activities()->latest('id')->limit($this->limit + 1)->get() ?? collect();

        return view('filament.projects.history', [
            'activities' => $activities->take($this->limit),
            'hasMore' => $activities->count() > $this->limit,
        ]);
    }
}
