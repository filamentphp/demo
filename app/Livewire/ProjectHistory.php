<?php

namespace App\Livewire;

use App\Livewire\Concerns\InteractsWithProjectHistory;
use App\Models\HR\Project;
use App\Models\User;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

class ProjectHistory extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithProjectHistory;
    use InteractsWithSchemas;

    protected User $historyActor;

    #[Locked]
    public ?Project $record = null;

    #[Locked]
    public int $limit = 20;

    public function boot(): void
    {
        $actor = auth()->user();
        abort_unless($actor !== null, 403);
        $this->historyActor = $actor;
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

    protected function historyProject(): ?Project
    {
        return $this->record;
    }

    protected function historyActor(): User
    {
        return $this->historyActor;
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
