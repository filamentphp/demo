<?php

namespace App\Livewire;

use App\Enums\ProjectStatus;
use App\Enums\TaskPriority;
use App\Filament\Resources\HR\Projects\ProjectResource;
use App\Models\HR\Project;
use App\Models\HR\ProjectActivity;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Support\Exceptions\Halt;
use Illuminate\Support\Facades\Context;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

class ProjectHistory extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    /** @var list<string> */
    protected const RESTORABLE_FIELDS = ['name', 'department_id', 'status', 'priority', 'color', 'start_date', 'end_date', 'budget', 'estimated_hours'];

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

    public function restoreFieldAction(): Action
    {
        return Action::make('restoreField')
            ->label('Restore')
            ->icon('heroicon-m-arrow-uturn-left')
            ->color('gray')
            ->size('xs')
            ->requiresConfirmation()
            ->modalHeading(fn (array $arguments): string => 'Restore ' . $this->fieldLabel((string) ($arguments['field'] ?? '')) . '?')
            ->modalDescription(function (array $arguments): string {
                $change = $this->restorableChange($arguments);

                return 'Value after this change: ' . $this->displayValue($change['new'] ?? null) . '. Restore to: ' . $this->displayValue($change['old'] ?? null) . '. This creates a new project update, only if the field has not changed since.';
            })
            ->modalSubmitActionLabel('Confirm restoration')
            ->action(fn (array $arguments) => $this->restoreField($arguments));
    }

    /** @param array<string, mixed> $arguments */
    protected function restoreField(array $arguments): void
    {
        $record = $this->record;
        abort_unless($record !== null, 404);

        $record->getConnection()->transaction(function () use ($arguments, $record): void {
            $project = Project::query()->whereKey($record->getKey())->lockForUpdate()->firstOrFail();
            ProjectResource::authorizeEdit($project);

            $activity = ProjectActivity::query()
                ->where('project_id', $project->getKey())
                ->whereKey($arguments['activity'] ?? null)
                ->lockForUpdate()
                ->first();
            abort_unless($activity !== null, 404);
            $field = (string) ($arguments['field'] ?? '');
            $change = $activity->raw_changes[$field] ?? null;

            abort_unless(in_array($field, self::RESTORABLE_FIELDS, true) && $activity->event === 'project_updated' && is_array($change) && array_key_exists('old', $change) && array_key_exists('new', $change), 404);

            if (! $this->rawValuesMatch($project->getRawOriginal($field), $change['new'])) {
                Notification::make()->danger()->title('Could not restore value')->body('This field has changed since that history entry. Refresh and review the latest value.')->send();

                throw new Halt;
            }

            $values = [...$project->getAttributes(), $field => $change['old']];
            validator($values, $this->projectRules())->validate();
            Context::scope(function () use ($project, $field, $change): void {
                $project->setAttribute($field, $change['old']);
                $project->save();
            }, hidden: ['project_history_actor' => $this->historyActor, 'project_history_interface' => 'panel']);
        });

        Notification::make()->success()->title('Project value restored')->send();
    }

    /** @param array<string, mixed> $arguments
     * @return array{old: scalar|null, new: scalar|null}
     */
    protected function restorableChange(array $arguments): array
    {
        $activity = ProjectActivity::query()->where('project_id', $this->record?->getKey())->whereKey($arguments['activity'] ?? null)->firstOrFail();
        $field = (string) ($arguments['field'] ?? '');
        $change = $activity->raw_changes[$field] ?? null;
        abort_unless(in_array($field, self::RESTORABLE_FIELDS, true) && is_array($change) && array_key_exists('old', $change) && array_key_exists('new', $change), 404);

        return $change;
    }

    /** @return array<string, array<int, mixed>> */
    protected function projectRules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'department_id' => ['nullable', 'integer', 'exists:departments,id'],
            'status' => ['required', Rule::enum(ProjectStatus::class)],
            'priority' => ['required', Rule::enum(TaskPriority::class)],
            'color' => ['nullable', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'budget' => ['required', 'numeric', 'min:0', 'max:9999999999.99'],
            'estimated_hours' => ['required', 'numeric', 'min:0', 'max:9999999.9'],
        ];
    }

    protected function rawValuesMatch(mixed $current, mixed $expected): bool
    {
        if ($current === null || $expected === null) {
            return $current === $expected;
        }

        return (string) $current === (string) $expected;
    }

    protected function fieldLabel(string $field): string
    {
        return $field === 'department_id' ? 'Department' : str($field)->replace('_', ' ')->ucfirst()->toString();
    }

    protected function displayValue(mixed $value): string
    {
        return blank($value) ? 'Not set' : (string) $value;
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
