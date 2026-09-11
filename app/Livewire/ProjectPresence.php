<?php

namespace App\Livewire;

use App\Filament\Resources\HR\Projects\ProjectResource;
use App\Models\HR\Project;
use App\Models\HR\ProjectRevision;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Context;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

class ProjectPresence extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    #[Locked]
    public Project $record;

    #[Locked]
    public ?int $mountedOwnerId = null;

    public function boot(): void
    {
        abort_unless(
            auth()->user()?->canAccessPanel(Filament::getPanel('admin')) === true,
            403,
        );

        abort_unless(Project::query()->whereKey($this->record->getKey())->exists(), 404);
    }

    /** @param array{projectId: int} $event */
    #[On('echo-private:projects,ProjectChanged')]
    public function projectChanged(array $event): void
    {
        if ((int) $event['projectId'] !== $this->record->id) {
            $this->skipRender();
        }
    }

    public function assignOwnerAction(): Action
    {
        return Action::make('assignOwner')->label('Change owner')->link()->size('sm')->color('gray')
            ->authorize(fn (): bool => ProjectResource::canEdit($this->record))
            ->modalHeading('Who owns this project?')
            ->schema([Select::make('owner_id')->label('Project owner')
                ->options(fn (): array => User::query()->orderBy('name')->get()
                    ->filter(fn (User $user): bool => ProjectRevision::canParticipate($this->record, $user))
                    ->pluck('name', 'id')->all())
                ->searchable()->preload()->placeholder('Unassigned')])
            ->mountUsing(function (?Schema $schema): void {
                $this->mountedOwnerId = $this->record->fresh()?->owner_id;
                $schema?->fill(['owner_id' => $this->mountedOwnerId]);
            })
            ->action(function (array $data): void {
                $this->record->getConnection()->transaction(function () use ($data): void {
                    $project = Project::query()->whereKey($this->record->id)->lockForUpdate()->firstOrFail();
                    ProjectResource::authorizeEdit($project);
                    if ($project->owner_id !== $this->mountedOwnerId) {
                        throw ValidationException::withMessages(['owner_id' => 'The owner changed. Reopen this action before assigning someone.']);
                    }
                    $owner = filled($data['owner_id'] ?? null) ? User::query()->whereKey($data['owner_id'])->firstOrFail() : null;
                    if ($owner && ! ProjectRevision::canParticipate($project, $owner)) {
                        throw ValidationException::withMessages(['owner_id' => 'Choose someone who can view and edit this project.']);
                    }
                    Context::scope(fn () => $project->update(['owner_id' => $owner?->id]), hidden: [
                        'project_history_actor' => auth()->user(), 'project_history_interface' => 'panel',
                        'project_history_reason' => 'ownership_assigned', 'project_history_source' => 'ownership',
                    ]);
                });
                Notification::make()->success()->title('Project owner updated')->send();
            });
    }

    public function render(): View
    {
        return view('livewire.project-presence', ['owner' => $this->record->fresh()?->owner]);
    }
}
