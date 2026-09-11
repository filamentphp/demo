<?php

namespace App\Livewire;

use App\Enums\TaskStatus;
use App\Filament\Resources\HR\Projects\ProjectResource;
use App\Filament\Resources\HR\Projects\Schemas\ProjectInfolist;
use App\Models\HR\Project;
use App\Models\HR\ProjectRevision;
use App\Models\HR\Task;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\RichEditor\RichContentRenderer;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Context;
use Illuminate\Support\HtmlString;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

class ProjectRevisions extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    protected User $revisionActor;

    #[Locked]
    public ?Project $record = null;

    #[Locked]
    public ?int $mountedRevisionVersion = null;

    #[Locked]
    public ?int $mountedOwnerId = null;

    /** @var array<string, mixed> */
    #[Locked]
    public array $mountedCurrentValues = [];

    /** @var array{name: string, budget: string, end_date: ?string, description: string}|null */
    #[Locked]
    public ?array $mountedBase = null;

    /** @var array<int, array<string, mixed>> */
    #[Locked]
    public array $mountedTasks = [];

    #[Locked]
    public ?int $previewRevisionId = null;

    public string $previewMode = 'proposed';

    public function boot(): void
    {
        $actor = auth()->user();
        abort_unless($actor !== null, 403);
        $this->revisionActor = $actor;
    }

    /** @param array{projectId: int} $event */
    #[On('echo-private:projects,ProjectChanged')]
    public function projectChanged(array $event): void
    {
        unset($this->cachedSchemas['previewInfolist']);
        if ((int) $event['projectId'] !== $this->record?->id) {
            $this->skipRender();
        }
    }

    public function proposeAction(): Action
    {
        return $this->revisionAction('propose')
            ->label('Propose revision')
            ->icon('heroicon-m-plus')
            ->schema($this->revisionForm())
            ->mountUsing(function (?Schema $schema): void {
                $project = $this->project();
                $this->mountedBase = ProjectRevision::snapshot($project);
                $this->mountedTasks = $project->tasks()->get()->mapWithKeys(fn (Task $task): array => [$task->id => ProjectRevision::taskSnapshot($task)])->all();
                $schema?->fill([...$this->mountedBase, 'reason' => '', 'tasks' => []]);
            })
            ->action(function (array $data): void {
                ProjectRevision::propose($this->project(), $this->revisionActor, $this->values($data), $data['reason'], $this->mountedBase, $this->taskValues($data), expectedTasks: $this->mountedTasks);
                Notification::make()->success()->title('Revision proposed')->body('Choose a teammate using Request review.')->send();
            });
    }

    public function assignOwnerAction(): Action
    {
        return $this->revisionAction('assignOwner')->label('Change owner')->icon('heroicon-m-user-circle')->color('gray')
            ->modalHeading('Who owns this project?')
            ->modalDescription('The project owner is accountable for delivery. Each revision has its own reviewer and next step.')
            ->schema([Select::make('owner_id')->label('Project owner')->options(fn (): array => $this->participants())->searchable()->preload()->placeholder('Unassigned')])
            ->mountUsing(function (?Schema $schema): void {
                $this->mountedOwnerId = $this->project()->owner_id;
                $schema?->fill(['owner_id' => $this->mountedOwnerId]);
            })
            ->action(function (array $data): void {
                $project = $this->project();
                $project->getConnection()->transaction(function () use ($project, $data): void {
                    $project = Project::query()->whereKey($project->id)->lockForUpdate()->firstOrFail();
                    if ($project->owner_id !== $this->mountedOwnerId) {
                        throw ValidationException::withMessages(['owner_id' => 'The owner changed. Reopen this action before assigning someone.']);
                    }
                    $owner = filled($data['owner_id'] ?? null) ? User::query()->whereKey($data['owner_id'])->firstOrFail() : null;
                    if ($owner && ! ProjectRevision::canParticipate($project, $owner)) {
                        throw ValidationException::withMessages(['owner_id' => 'Choose someone who can view and edit this project.']);
                    }
                    Context::scope(fn () => $project->update(['owner_id' => $owner?->id]), hidden: [
                        'project_history_actor' => $this->revisionActor, 'project_history_interface' => 'panel',
                        'project_history_reason' => 'ownership_assigned', 'project_history_source' => 'ownership',
                    ]);
                });
                Notification::make()->success()->title('Project owner updated')->send();
            });
    }

    public function requestReviewAction(): Action
    {
        return $this->reviewAction('requestReview', 'Request review', 'gray')->icon('heroicon-m-user-plus')
            ->modalHeading('Request a teammate’s review')
            ->modalDescription('This person owns the review step. Reassigning a review removes it from the previous reviewer’s queue.')
            ->schema(fn (array $arguments): array => [
                Select::make('requested_reviewer_id')->label('Reviewer')->required()->searchable()->preload()
                    ->options(fn (): array => $this->participants($this->revision($arguments)->author_id)),
            ])
            ->action(function (array $arguments, array $data): void {
                $this->revision($arguments)->requestReview($this->revisionActor, $this->mountedRevisionVersion ?? 0, User::query()->whereKey($data['requested_reviewer_id'])->firstOrFail());
                Notification::make()->success()->title('Review requested')->send();
            });
    }

    /** @return array<int, string> */
    public function participants(?int $except = null): array
    {
        $project = $this->project();

        return User::query()->orderBy('name')->get()
            ->filter(fn (User $user): bool => $user->id !== $except && ProjectRevision::canParticipate($project, $user))
            ->pluck('name', 'id')->all();
    }

    public function reviseAction(): Action
    {
        return $this->revisionAction('revise')
            ->label(fn (array $arguments): string => $this->revision($arguments)->status === 'changes_requested' ? 'Edit & resubmit' : 'Edit proposal')
            ->icon('heroicon-m-pencil-square')
            ->color('gray')
            ->schema($this->revisionForm())
            ->mountUsing(function (array $arguments, ?Schema $schema): void {
                $revision = $this->revision($arguments);
                $this->mountedRevisionVersion = (int) ($arguments['version'] ?? 0);
                $this->mountedTasks = $this->project()->tasks()->get()->mapWithKeys(fn (Task $task): array => [$task->id => ProjectRevision::taskSnapshot($task)])->all();
                $baseValues = $revision->getAttribute('base_values');
                $proposedValues = $revision->getAttribute('proposed_values');
                abort_unless(is_array($baseValues) && is_array($proposedValues), 500);
                $schema?->fill([...array_replace($baseValues, $proposedValues), 'reason' => $revision->reason,
                    'tasks' => collect($revision->proposed_tasks ?? [])->map(fn (array $values, int $id): array => ['task_id' => $id, ...array_replace($revision->base_tasks[$id] ?? [], $values)])->values()->all(),
                ]);
            })
            ->action(function (array $arguments, array $data): void {
                $this->revision($arguments)->revise($this->revisionActor, $this->mountedRevisionVersion ?? 0, $this->values($data), $data['reason'], $this->taskValues($data), $this->mountedTasks);
                Notification::make()->success()->title('Revision resubmitted')->send();
            });
    }

    public function resolveStaleAction(): Action
    {
        return $this->revisionAction('resolveStale')
            ->label('Resolve conflicts')
            ->icon('heroicon-m-arrows-right-left')
            ->color('warning')
            ->schema(function (array $arguments): array {
                return collect($this->revision($arguments)->staleFields())->map(function (array $conflict, string $field): Radio {
                    return Radio::make("choices.{$field}")
                        ->label($this->fieldLabel($field))
                        ->options([
                            'current' => 'Keep current — ' . $this->plainValue($field, $conflict['current']),
                            'proposed' => 'Use proposal — ' . $this->plainValue($field, $conflict['proposed']),
                        ])
                        ->required();
                })->all();
            })
            ->mountUsing(function (array $arguments, ?Schema $schema): void {
                $revision = $this->revision($arguments);
                $stale = $revision->staleFields();
                $this->mountedRevisionVersion = (int) ($arguments['version'] ?? 0);
                $this->mountedCurrentValues = array_map(fn (array $conflict): mixed => $conflict['current'], $stale);
                $schema?->fill(['choices' => array_fill_keys(array_keys($stale), 'proposed')]);
            })
            ->action(function (array $arguments, array $data): void {
                $this->revision($arguments)->resolveStale($this->revisionActor, $this->mountedRevisionVersion ?? 0, $data['choices'] ?? [], $this->mountedCurrentValues);
                Notification::make()->success()->title('Conflicts resolved')->send();
            });
    }

    public function requestChangesAction(): Action
    {
        return $this->reviewAction('requestChanges', 'Request changes', 'warning')
            ->schema([Textarea::make('feedback')->required()->maxLength(5000)->rows(5)])
            ->action(function (array $arguments, array $data): void {
                $this->revision($arguments)->requestChanges($this->revisionActor, $this->mountedRevisionVersion ?? 0, $data['feedback']);
                Notification::make()->success()->title('Changes requested')->send();
            });
    }

    public function approveAction(): Action
    {
        return $this->reviewAction('approve', 'Approve & apply', 'success')
            ->icon('heroicon-m-check-circle')
            ->requiresConfirmation()
            ->modalHeading('Apply this revision?')
            ->modalDescription('Apply the project and task changes together. This creates one history event. You can propose a rollback later.')
            ->modalSubmitActionLabel('Approve and apply')
            ->action(function (array $arguments): void {
                $this->revision($arguments)->approve($this->revisionActor, $this->mountedRevisionVersion ?? 0);
                Notification::make()->success()->title('Revision approved and applied')->send();
            });
    }

    public function rejectAction(): Action
    {
        return $this->reviewAction('reject', 'Reject', 'danger')
            ->icon('heroicon-m-x-circle')
            ->requiresConfirmation()
            ->action(function (array $arguments): void {
                $this->revision($arguments)->reject($this->revisionActor, $this->mountedRevisionVersion ?? 0);
                Notification::make()->success()->title('Revision rejected')->send();
            });
    }

    public function withdrawAction(): Action
    {
        return $this->reviewAction('withdraw', 'Withdraw', 'danger')
            ->icon('heroicon-m-trash')
            ->requiresConfirmation()
            ->modalDescription('This closes the proposal without changing the project.')
            ->action(function (array $arguments): void {
                $this->revision($arguments)->reject($this->revisionActor, $this->mountedRevisionVersion ?? 0);
                Notification::make()->success()->title('Revision withdrawn')->send();
            });
    }

    public function openDiscussion(int $revisionId): void
    {
        $revision = $this->revision(['revision' => $revisionId]);
        abort_unless($revision->thread_id !== null, 404);
        $this->dispatch('open-page-chat', room: hash('sha256', '/projects/' . $this->project()->id), threadId: $revision->thread_id);
    }

    public function rollbackAction(): Action
    {
        return $this->revisionAction('rollback')->label('Propose rollback')->icon('heroicon-m-arrow-uturn-left')->color('gray')
            ->modalDescription('Create a new reverse proposal. Later changes are flagged for resolution; nothing is immediately restored.')
            ->schema([Textarea::make('reason')->required()->maxLength(5000)])
            ->action(function (array $arguments, array $data): void {
                $this->revision($arguments)->proposeRollback($this->revisionActor, $data['reason']);
                Notification::make()->success()->title('Rollback proposed')->body('Review the reverse diff and resolve any later changes before approval.')->send();
            });
    }

    public function previewAction(): Action
    {
        return Action::make('preview')->label('Preview')->icon('heroicon-m-eye')->color('gray')
            ->modalWidth('5xl')->modalHeading('Preview revision')->modalSubmitAction(false)->modalCancelActionLabel('Close')
            ->mountUsing(function (array $arguments): void {
                $this->previewRevisionId = $this->revision($arguments)->id;
                $this->previewMode = 'proposed';
            })
            ->modalContent(fn (): View => view('filament.projects.revision-preview', ['project' => $this->previewProject()]));
    }

    public function previewProject(): Project
    {
        abort_unless(in_array($this->previewMode, ['current', 'proposed'], true), 422);
        $revision = $this->revision(['revision' => $this->previewRevisionId]);
        $project = $this->project()->load('tasks');
        if ($this->previewMode === 'proposed') {
            $project->fill($revision->proposed_values);
            foreach ($project->tasks as $task) {
                $task->fill($revision->proposed_tasks[$task->id] ?? []);
            }
        }

        return $project;
    }

    public function previewInfolist(Schema $schema): Schema
    {
        return ProjectInfolist::configure($schema->record($this->previewProject()), preview: true);
    }

    public function updatedPreviewMode(): void
    {
        unset($this->cachedSchemas['previewInfolist']);
    }

    /** @return array<int, TextInput|DatePicker|RichEditor|Textarea|Repeater> */
    protected function revisionForm(): array
    {
        return [
            Textarea::make('reason')->required()->maxLength(5000)->rows(3)->columnSpanFull(),
            TextInput::make('name')->required()->maxLength(255),
            TextInput::make('budget')->required()->numeric()->prefix('$')->minValue(0)->maxValue(9999999999.99),
            DatePicker::make('end_date')->minDate($this->record?->start_date),
            RichEditor::make('description')->toolbarButtons([['bold', 'italic', 'link'], ['bulletList', 'orderedList', 'blockquote']])->columnSpanFull(),
            Repeater::make('tasks')->label('Related task changes')->defaultItems(0)->addActionLabel('Include a task')
                ->helperText('These changes share the same reason, discussion and approval. Removing a row only removes it from this proposal.')
                ->schema([
                    Select::make('task_id')->label('Task')->required()->distinct()->options(fn (): array => $this->record?->tasks()->pluck('title', 'id')->all() ?? [])->live()
                        ->afterStateUpdated(function (mixed $state, Set $set): void {
                            $task = $this->project()->tasks()->whereKey($state)->first();
                            if ($task) {
                                foreach (ProjectRevision::taskSnapshot($task) as $field => $value) {
                                    $set($field, $value);
                                }
                            }
                        }),
                    TextInput::make('title')->required()->maxLength(255),
                    Select::make('status')->options(TaskStatus::class)->required(),
                    DatePicker::make('due_date'),
                ])->columns(2)->columnSpanFull(),
        ];
    }

    protected function reviewAction(string $name, string $label, string $color): Action
    {
        return $this->revisionAction($name)->label($label)->color($color)
            ->mountUsing(function (array $arguments, ?Schema $schema): void {
                $this->revision($arguments);
                $this->mountedRevisionVersion = (int) ($arguments['version'] ?? 0);
                $schema?->fill();
            });
    }

    protected function revisionAction(string $name): Action
    {
        return Action::make($name)->modalContent(fn (): HtmlString => new HtmlString(
            $this->getErrorBag()->any() ? '<p role="alert" class="text-sm text-danger-600">' . e($this->getErrorBag()->first()) . '</p>' : '',
        ));
    }

    /** @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    protected function values(array $data): array
    {
        return collect(ProjectRevision::Fields)->mapWithKeys(fn (string $field): array => [$field => $data[$field] ?? null])->all();
    }

    /** @param array<string, mixed> $data
     * @return array<int, array<string, mixed>>
     */
    protected function taskValues(array $data): array
    {
        $values = [];
        foreach ($data['tasks'] ?? [] as $task) {
            $values[(int) $task['task_id']] = Arr::only($task, ['title', 'status', 'due_date']);
        }

        return $values;
    }

    /** @param array<string, mixed> $arguments */
    protected function revision(array $arguments): ProjectRevision
    {
        return ProjectRevision::query()
            ->where('project_id', $this->project()->getKey())
            ->whereKey($arguments['revision'] ?? null)
            ->with(['author', 'reviewer', 'requestedReviewer'])
            ->firstOrFail();
    }

    protected function project(): Project
    {
        abort_unless($this->record !== null, 404);
        ProjectResource::authorizeEdit($this->record);

        return Project::query()->whereKey($this->record->getKey())->firstOrFail();
    }

    public function fieldLabel(string $field): string
    {
        if (preg_match('/^task_(\d+)_(.+)$/', $field, $matches)) {
            return 'Task #' . $matches[1] . ' · ' . str($matches[2])->replace('_', ' ')->title();
        }

        return str($field)->replace('_', ' ')->title()->toString();
    }

    public function plainValue(string $field, mixed $value): string
    {
        if (blank($value)) {
            return 'Not set';
        }

        if ($field === 'description') {
            return str(strip_tags((string) $value))->squish()->limit(100)->toString();
        }

        return $field === 'budget' ? '$' . number_format((float) $value, 2) : (string) $value;
    }

    public function richValue(string $field, mixed $value): HtmlString | string
    {
        if (blank($value)) {
            return e('Not set');
        }

        if ($field !== 'description') {
            return e($this->plainValue($field, $value));
        }

        return new HtmlString(RichContentRenderer::make($value)->toHtml());
    }

    public function statusColor(string $status): string
    {
        return match ($status) {
            'pending' => 'info', 'changes_requested' => 'warning', 'applied' => 'success', default => 'danger',
        };
    }

    public function render(): View
    {
        if ($this->record !== null) {
            ProjectResource::authorizeView($this->record);
        }

        $revisions = $this->record === null ? collect() : ProjectRevision::query()
            ->where('project_id', $this->record->getKey())->with(['author', 'reviewer', 'requestedReviewer'])->latest('id')->get();

        return view('livewire.project-revisions', ['revisions' => $revisions, 'projectOwner' => $this->record?->fresh()?->owner]);
    }
}
