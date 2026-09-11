<?php

namespace App\Models\HR;

use App\Enums\TaskStatus;
use App\Events\PageChatChanged;
use App\Filament\Resources\HR\Projects\ProjectResource;
use App\Filament\Resources\HR\Tasks\TaskResource;
use App\Models\PageMessage;
use App\Models\User;
use Carbon\Carbon;
use Closure;
use Filament\Facades\Filament;
use Filament\Forms\Components\RichEditor\RichContentRenderer;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\QueryException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Context;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

/**
 * @property array<string, mixed> $base_values
 * @property array<string, mixed> $proposed_values
 * @property array<int, array<string, mixed>>|null $base_tasks
 * @property array<int, array<string, mixed>>|null $proposed_tasks
 * @property int $version
 * @property User $author
 */
class ProjectRevision extends Model
{
    public const Fields = ['name', 'budget', 'end_date', 'description'];

    protected $guarded = [];

    /** @var array<string, string> */
    protected $casts = [
        'base_values' => 'array',
        'proposed_values' => 'array',
        'base_tasks' => 'array',
        'proposed_tasks' => 'array',
        'version' => 'integer',
        'applied_at' => 'datetime',
        'review_requested_at' => 'datetime',
    ];

    /** @return array{name: string, budget: string, end_date: ?string, description: string} */
    public static function snapshot(Project $project): array
    {
        return [
            'name' => (string) $project->name,
            'budget' => number_format((float) $project->budget, 2, '.', ''),
            'end_date' => $project->end_date?->format('Y-m-d'),
            'description' => (string) $project->description,
        ];
    }

    /**
     * @param  array<string, mixed>  $values
     * @param  array{name: string, budget: string, end_date: ?string, description: string}|null  $expectedBase
     * @param  array<int, array<string, mixed>>  $taskValues
     * @param  array<int, array<string, mixed>>|null  $expectedTasks
     */
    public static function propose(Project $project, User $author, array $values, string $reason, ?array $expectedBase = null, array $taskValues = [], ?PageMessage $sourceMessage = null, ?array $expectedTasks = null, ?User $requestedReviewer = null): self
    {
        self::authorizeProject($project, $author);
        $reason = trim($reason);

        Validator::make(['reason' => $reason], ['reason' => ['required', 'string', 'max:5000']])->validate();

        self::validateKeys($values);
        if ($sourceMessage && ($sourceMessage->user_id !== $author->id || $sourceMessage->room !== self::room($project))) {
            throw new AuthorizationException('The source request must belong to the author and this project.');
        }

        try {
            return $project->getConnection()->transaction(function () use ($project, $author, $values, $reason, $expectedBase, $taskValues, $sourceMessage, $expectedTasks, $requestedReviewer): self {
                $lockedProject = Project::query()->whereKey($project->getKey())->lockForUpdate()->firstOrFail();
                if ($requestedReviewer) {
                    self::validateReviewer($lockedProject, $author, $requestedReviewer);
                }
                $base = static::snapshot($lockedProject);
                if ($expectedBase !== null && $base !== $expectedBase) {
                    throw ValidationException::withMessages(['revision' => 'The project changed while you were preparing this proposal. Copy your changes, then reopen the proposal to review the latest values.']);
                }
                $candidate = self::candidate($base, $values);
                self::validateDates($lockedProject, $candidate);
                $proposed = self::differences($base, $candidate);
                [$baseTasks, $proposedTasks] = self::taskChanges($lockedProject, $taskValues, expected: $expectedTasks);

                if ($proposed === [] && $proposedTasks === []) {
                    throw ValidationException::withMessages(['values' => 'The revision must contain an effective change.']);
                }

                $revision = static::query()->create([
                    'project_id' => $lockedProject->id,
                    'open_project_id' => $lockedProject->id,
                    'author_id' => $author->id,
                    'requested_reviewer_id' => $requestedReviewer?->id,
                    'review_requested_at' => $requestedReviewer ? now() : null,
                    'status' => 'pending',
                    'reason' => $reason,
                    'base_values' => $base,
                    'proposed_values' => $proposed,
                    'base_tasks' => $baseTasks,
                    'proposed_tasks' => $proposedTasks,
                    'source_message_id' => $sourceMessage?->id,
                ]);
                $thread = PageMessage::query()->create([
                    'room' => self::room($lockedProject),
                    'user_id' => $author->id,
                    'body' => "Project revision #{$revision->id}: {$lockedProject->name}\nProposed by {$author->name}. Reason: {$reason}" . ($requestedReviewer ? "\nReview requested from {$requestedReviewer->name}." : "\n{$author->name} will choose a reviewer."),
                    'body_format' => 'text',
                    'is_agent' => false,
                ]);
                $revision->update(['thread_id' => $thread->id]);
                PageChatChanged::dispatch($thread->room, $thread->id, 'created');
                ProjectActivity::notify($lockedProject->id, 'revision_proposed', entityType: 'revision', entityId: $revision->id, fields: ['status', 'requested_reviewer_id'], reason: 'proposal_created', source: $sourceMessage ? 'agent_proposal' : 'revision');

                return $revision->refresh();
            });
        } catch (QueryException $exception) {
            if (static::query()->where('open_project_id', $project->id)->exists()) {
                throw ValidationException::withMessages(['project' => 'This project already has an open revision.']);
            }

            throw $exception;
        }
    }

    /**
     * @param  array<string, mixed>  $values
     * @param  array<int, array<string, mixed>>|null  $taskValues
     * @param  array<int, array<string, mixed>>|null  $expectedTasks
     */
    public function revise(User $author, int $expectedVersion, array $values, string $reason, ?array $taskValues = null, ?array $expectedTasks = null): void
    {
        $this->mutate($author, $expectedVersion, function (self $revision, Project $project) use ($author, $values, $reason, $taskValues, $expectedTasks): void {
            $revision->authorizeAuthor($author);

            if (! in_array($revision->status, ['pending', 'changes_requested'], true)) {
                self::invalidState();
            }

            self::validateKeys($values);
            $reason = trim($reason);
            Validator::make(['reason' => $reason], ['reason' => ['required', 'string', 'max:5000']])->validate();

            $candidate = self::candidate(array_replace($revision->base_values, $revision->proposed_values), $values);
            self::validateDates($project, $candidate);
            $proposed = self::differences($revision->base_values, $candidate);
            [$baseTasks, $proposedTasks] = $taskValues === null
                ? [$revision->base_tasks ?? [], $revision->proposed_tasks ?? []]
                : self::taskChanges($project, $taskValues, $revision->base_tasks ?? [], $expectedTasks);
            if ($proposed === [] && $proposedTasks === []) {
                throw ValidationException::withMessages(['values' => 'The revision must contain an effective change.']);
            }

            $revision->update([
                'proposed_values' => $proposed,
                'base_tasks' => $baseTasks,
                'proposed_tasks' => $proposedTasks,
                'reason' => $reason,
                'status' => 'pending',
                'reviewer_id' => null,
                'version' => $revision->version + 1,
            ]);
            $revision->appendMessage($project, $author, "Revision resubmitted (version {$revision->version}). Reason: {$reason}", event: 'revision_resubmitted');
        });
    }

    /** @return array<string, array{base: mixed, current: mixed, proposed: mixed}> */
    public function staleFields(): array
    {
        $current = static::snapshot($this->project()->firstOrFail());
        $stale = [];

        foreach ($this->proposed_values as $field => $proposed) {
            if (($this->base_values[$field] ?? null) !== $current[$field]) {
                $stale[$field] = ['base' => $this->base_values[$field] ?? null, 'current' => $current[$field], 'proposed' => $proposed];
            }
        }

        $tasks = $this->project()->firstOrFail()->tasks()->whereKey(array_keys($this->proposed_tasks ?? []))->get()->keyBy('id');
        foreach ($this->proposed_tasks ?? [] as $id => $changes) {
            $task = $tasks->get($id);
            $snapshot = $task ? self::taskSnapshot($task) : [];
            foreach ($changes as $field => $proposed) {
                $base = $this->base_tasks[$id][$field] ?? null;
                if (! $task || $base !== ($snapshot[$field] ?? null)) {
                    $stale["task_{$id}_{$field}"] = ['base' => $base, 'current' => $task ? ($snapshot[$field] ?? null) : '[Task removed]', 'proposed' => $proposed];
                }
            }
        }

        return $stale;
    }

    /**
     * @param  array<string, 'current'|'proposed'>  $choices
     * @param  array<string, mixed>|null  $expectedCurrent
     */
    public function resolveStale(User $author, int $expectedVersion, array $choices, ?array $expectedCurrent = null): void
    {
        $this->mutate($author, $expectedVersion, function (self $revision, Project $project) use ($author, $choices, $expectedCurrent): void {
            $revision->authorizeAuthor($author);
            if (! in_array($revision->status, ['pending', 'changes_requested'], true)) {
                self::invalidState();
            }

            $stale = $revision->staleFields();
            if ($expectedCurrent !== null && $expectedCurrent !== array_map(fn (array $conflict): mixed => $conflict['current'], $stale)) {
                throw ValidationException::withMessages(['choices' => 'The project changed again. Close and reopen resolution to review the latest values.']);
            }
            if (array_diff_key($choices, $stale) !== [] || array_diff_key($stale, $choices) !== [] || array_diff($choices, ['current', 'proposed']) !== []) {
                throw ValidationException::withMessages(['choices' => 'Choose current or proposed for every stale field.']);
            }

            $base = $revision->base_values;
            $proposed = $revision->proposed_values;
            $baseTasks = $revision->base_tasks ?? [];
            $proposedTasks = $revision->proposed_tasks ?? [];
            foreach ($stale as $field => $conflict) {
                if (preg_match('/^task_(\d+)_(.+)$/', $field, $matches)) {
                    [, $id, $taskField] = $matches;
                    if ($choices[$field] === 'proposed' && $conflict['current'] === '[Task removed]') {
                        throw ValidationException::withMessages(['choices' => 'A removed task cannot be updated. Keep current to remove it from the proposal.']);
                    }
                    $baseTasks[$id][$taskField] = $conflict['current'];
                    if ($choices[$field] === 'current') {
                        unset($proposedTasks[$id][$taskField]);
                    }

                    continue;
                }
                $base[$field] = $conflict['current'];
                if ($choices[$field] === 'current') {
                    unset($proposed[$field]);
                }
            }
            $proposedTasks = array_filter($proposedTasks);
            if ($proposed === [] && $proposedTasks === []) {
                throw ValidationException::withMessages(['choices' => 'Resolving to current values would leave no proposed changes. Withdraw the revision instead.']);
            }

            $revision->update(['base_values' => $base, 'proposed_values' => $proposed, 'base_tasks' => $baseTasks, 'proposed_tasks' => $proposedTasks, 'version' => $revision->version + 1]);
            $revision->appendMessage($project, $author, "Stale fields resolved (version {$revision->version}).", event: 'revision_conflicts_resolved');
        });
    }

    public function requestChanges(User $reviewer, int $expectedVersion, string $feedback): void
    {
        $this->mutate($reviewer, $expectedVersion, function (self $revision, Project $project) use ($reviewer, $feedback): void {
            $revision->authorizeReviewer($reviewer);
            if ($revision->status !== 'pending') {
                self::invalidState();
            }
            $feedback = trim($feedback);
            Validator::make(['feedback' => $feedback], ['feedback' => ['required', 'string', 'max:5000']])->validate();
            $revision->update(['status' => 'changes_requested', 'reviewer_id' => $reviewer->id, 'feedback' => $feedback, 'version' => $revision->version + 1]);
            $revision->appendMessage($project, $reviewer, "Changes requested: {$feedback}", event: 'revision_changes_requested');
        });
    }

    public function requestReview(User $actor, int $expectedVersion, User $reviewer): void
    {
        $this->mutate($actor, $expectedVersion, function (self $revision, Project $project) use ($actor, $reviewer): void {
            if ($actor->id !== $revision->author_id && $actor->id !== $revision->requested_reviewer_id) {
                throw new AuthorizationException('Only the author or requested reviewer may hand off this review.');
            }
            if (! in_array($revision->status, ['pending', 'changes_requested'], true)) {
                self::invalidState();
            }
            self::validateReviewer($project, $revision->author, $reviewer);
            if ($revision->requested_reviewer_id === $reviewer->id) {
                throw ValidationException::withMessages(['requested_reviewer_id' => 'This person is already the requested reviewer.']);
            }
            $previous = $revision->requestedReviewer?->name;
            $revision->update([
                'requested_reviewer_id' => $reviewer->id,
                'review_requested_at' => now(),
                'version' => $revision->version + 1,
            ]);
            $revision->appendMessage($project, $actor, $previous
                ? "Review handed off from {$previous} to {$reviewer->name}."
                : "Review requested from {$reviewer->name}.", event: 'revision_review_requested');
        });
    }

    public function nextStepOwner(): ?User
    {
        if (! in_array($this->status, ['pending', 'changes_requested'], true)) {
            return null;
        }

        return $this->status === 'changes_requested' || ! $this->requested_reviewer_id || $this->staleFields() !== []
            ? $this->author
            : $this->requestedReviewer;
    }

    public function nextStep(): string
    {
        return match (true) {
            ! in_array($this->status, ['pending', 'changes_requested'], true) => 'Complete',
            $this->staleFields() !== [] => 'Resolve changed values',
            $this->status === 'changes_requested' => 'Revise and resubmit',
            ! $this->requested_reviewer_id => 'Choose a reviewer',
            default => 'Review the proposal',
        };
    }

    public static function canParticipate(Project $project, User $user): bool
    {
        return $user->canAccessPanel(Filament::getPanel('admin'))
            && (! Gate::getPolicyFor($project) || (Gate::forUser($user)->allows('view', $project) && Gate::forUser($user)->allows('update', $project)));
    }

    private static function validateReviewer(Project $project, User $author, User $reviewer): void
    {
        if ($reviewer->id === $author->id || ! self::canParticipate($project, $reviewer)) {
            throw ValidationException::withMessages(['requested_reviewer_id' => 'Choose a different teammate who can view and edit this project.']);
        }
    }

    public function approve(User $reviewer, int $expectedVersion): void
    {
        $this->mutate($reviewer, $expectedVersion, function (self $revision, Project $project) use ($reviewer): void {
            $revision->authorizeReviewer($reviewer);
            if ($revision->status !== 'pending') {
                self::invalidState();
            }
            if ($revision->staleFields() !== []) {
                throw ValidationException::withMessages(['revision' => 'The project changed after this revision was proposed. Resolve stale fields first.']);
            }

            $candidate = array_replace(static::snapshot($project), $revision->proposed_values);
            self::validateCandidate($candidate);
            self::validateDates($project, $candidate);
            $author = $revision->author()->firstOrFail();
            $summary = str(strip_tags("Revision #{$revision->id} proposed by {$author->name}. Reason: {$revision->reason}"))->limit(252)->toString();
            Context::scope(function () use ($project, $revision): void {
                $project->fill($revision->proposed_values);
                if (array_key_exists('description', $revision->proposed_values)) {
                    $project->description_state = null;
                    $project->description_version = ($project->description_version ?? 0) + 1;
                }
                $project->save();
                foreach ($revision->proposed_tasks ?? [] as $id => $values) {
                    $task = $project->tasks()->whereKey($id)->firstOrFail();
                    TaskResource::authorizeEdit($task);
                    $task->update($values);
                }
                $fields = array_keys($revision->proposed_values);
                foreach ($revision->proposed_tasks ?? [] as $id => $values) {
                    foreach (array_keys($values) as $field) {
                        $fields[] = "task_{$id}_{$field}";
                    }
                }
                ProjectActivity::record($project->id, 'revision_applied', changes: $revision->historyChanges(), entityType: 'revision', entityId: $revision->id, fields: $fields, reason: 'revision_approved', source: 'revision');
            }, hidden: ['project_history_actor' => $reviewer, 'project_history_interface' => 'panel', 'project_history_revision_summary' => $summary, 'project_history_revision_id' => $revision->id]);
            $revision->update([
                'status' => 'applied', 'open_project_id' => null, 'reviewer_id' => $reviewer->id,
                'version' => $revision->version + 1, 'applied_at' => now(),
            ]);
            $revision->appendMessage($project, $reviewer, "Revision by {$author->name} applied. Reason: {$revision->reason}", false);
        });
    }

    public function reject(User $user, int $expectedVersion): void
    {
        $this->mutate($user, $expectedVersion, function (self $revision, Project $project) use ($user): void {
            if ($user->id !== $revision->author_id) {
                $revision->authorizeReviewer($user);
            }
            if (! in_array($revision->status, ['pending', 'changes_requested'], true)) {
                self::invalidState();
            }
            $revision->update(['status' => 'rejected', 'open_project_id' => null, 'reviewer_id' => $user->id === $revision->author_id ? $revision->reviewer_id : $user->id, 'version' => $revision->version + 1]);
            $revision->appendMessage($project, $user, $user->id === $revision->author_id ? 'Revision withdrawn.' : 'Revision rejected.', event: $user->id === $revision->author_id ? 'revision_withdrawn' : 'revision_rejected');
        });
    }

    private function mutate(User $user, int $expectedVersion, callable $callback): void
    {
        $project = $this->project()->firstOrFail();
        self::authorizeProject($project, $user);
        $this->getConnection()->transaction(function () use ($user, $expectedVersion, $callback): void {
            $revision = static::query()->whereKey($this->id)->lockForUpdate()->firstOrFail();
            $project = Project::query()->whereKey($revision->project_id)->lockForUpdate()->firstOrFail();
            $project->tasks()->whereKey(array_keys($revision->proposed_tasks ?? []))->orderBy('id')->lockForUpdate()->get();
            self::authorizeProject($project, $user);
            if ($revision->version !== $expectedVersion) {
                throw ValidationException::withMessages(['version' => 'This revision has changed. Refresh and try again.']);
            }
            $callback($revision, $project);
        });
        $this->refresh();
    }

    private static function authorizeProject(Project $project, User $user): void
    {
        if (auth()->id() !== $user->id || ! ProjectResource::canView($project) || ! ProjectResource::canEdit($project)) {
            throw new AuthorizationException('You are not allowed to revise this project.');
        }
    }

    /** @return array{title: string, status: string, due_date: ?string} */
    public static function taskSnapshot(Task $task): array
    {
        return ['title' => $task->title, 'status' => $task->status->value, 'due_date' => $task->due_date?->format('Y-m-d')];
    }

    /**
     * @param  array<int, array<string, mixed>>  $values
     * @param  array<int, array<string, mixed>>  $base
     * @param  array<int, array<string, mixed>>|null  $expected
     * @return array{array<int, array<string, mixed>>, array<int, array<string, mixed>>}
     */
    private static function taskChanges(Project $project, array $values, array $base = [], ?array $expected = null): array
    {
        $proposed = [];
        ksort($values);
        foreach ($values as $id => $fields) {
            $task = $project->tasks()->whereKey($id)->lockForUpdate()->firstOrFail();
            TaskResource::authorizeEdit($task);
            $current = self::taskSnapshot($task);
            if ($expected !== null && ($expected[$id] ?? null) !== $current) {
                throw ValidationException::withMessages(['tasks' => 'A selected task changed. Reopen the proposal to review its latest values.']);
            }
            if (array_diff(array_keys($fields), ['title', 'status', 'due_date']) !== []) {
                throw ValidationException::withMessages(['tasks' => 'Only task title, status and due date can be proposed.']);
            }
            $base[$id] ??= $current;
            $candidate = array_replace($base[$id], $fields);
            Validator::make($candidate, [
                'title' => ['required', 'string', 'max:255'],
                'status' => ['required', Rule::enum(TaskStatus::class)],
                'due_date' => ['nullable', 'date'],
            ])->validate();
            $candidate['due_date'] = blank($candidate['due_date']) ? null : Carbon::parse($candidate['due_date'])->format('Y-m-d');
            $changes = self::differences($base[$id], $candidate);
            if ($changes !== []) {
                $proposed[$id] = $changes;
            }
        }

        return [$base, $proposed];
    }

    /** @return array<string, array{old: ?string, new: ?string}> */
    public function historyChanges(): array
    {
        $changes = [];
        foreach ($this->proposed_values as $field => $value) {
            $changes[$field] = $field === 'description'
                ? ['old' => null, 'new' => 'Updated']
                : ['old' => $this->base_values[$field] === null ? null : (string) $this->base_values[$field], 'new' => $value === null ? null : (string) $value];
        }
        foreach ($this->proposed_tasks ?? [] as $id => $fields) {
            foreach ($fields as $field => $value) {
                $old = $this->base_tasks[$id][$field] ?? null;
                $changes["Task #{$id} · {$field}"] = ['old' => $old === null ? null : (string) $old, 'new' => $value === null ? null : (string) $value];
            }
        }

        return $changes;
    }

    public function proposeRollback(User $author, string $reason): self
    {
        return $this->getConnection()->transaction(function () use ($author, $reason): self {
            $source = self::query()->whereKey($this->id)->lockForUpdate()->firstOrFail();
            if ($source->status !== 'applied') {
                throw ValidationException::withMessages(['revision' => 'Only an applied revision can be rolled back.']);
            }
            $project = $source->project()->lockForUpdate()->firstOrFail();
            $values = array_intersect_key($source->base_values, $source->proposed_values);
            $tasks = [];
            foreach ($source->proposed_tasks ?? [] as $id => $fields) {
                $tasks[$id] = array_intersect_key($source->base_tasks[$id] ?? [], $fields);
            }
            $rollback = self::propose($project, $author, $values, $reason, taskValues: $tasks);
            $base = array_replace($rollback->base_values, $source->proposed_values);
            $baseTasks = $rollback->base_tasks ?? [];
            foreach ($source->proposed_tasks ?? [] as $id => $fields) {
                $baseTasks[$id] = array_replace($baseTasks[$id], $fields);
            }
            $rollback->update(['rollback_of_id' => $source->id, 'base_values' => $base, 'base_tasks' => $baseTasks]);

            return $rollback;
        });
    }

    private function authorizeAuthor(User $user): void
    {
        if ($user->id !== $this->author_id) {
            throw new AuthorizationException('Only the revision author may perform this action.');
        }
    }

    private function authorizeReviewer(User $user): void
    {
        if ($user->id === $this->author_id || $user->id !== $this->requested_reviewer_id) {
            throw new AuthorizationException('Only the requested reviewer may review this revision.');
        }
    }

    /**
     * @param  array<string, mixed>  $base
     * @param  array<string, mixed>  $values
     * @return array{name: string, budget: string, end_date: ?string, description: string}
     */
    private static function candidate(array $base, array $values): array
    {
        $candidate = array_replace($base, Arr::only($values, self::Fields));
        self::validateCandidate($candidate);
        $description = $candidate['description'];

        if (array_key_exists('description', $values) && $description !== null) {
            $description = RichContentRenderer::make($description)->toHtml();
            if ($description === RichContentRenderer::make($base['description'] ?? '')->toHtml()) {
                $description = $base['description'];
            }
        }

        return [
            'name' => trim((string) $candidate['name']),
            'budget' => number_format((float) $candidate['budget'], 2, '.', ''),
            'end_date' => blank($candidate['end_date']) ? null : Carbon::parse((string) $candidate['end_date'])->format('Y-m-d'),
            'description' => (string) $description,
        ];
    }

    /** @param array<string, mixed> $candidate */
    private static function validateCandidate(array $candidate): void
    {
        Validator::make($candidate, [
            'name' => ['required', 'string', 'max:255'],
            'budget' => ['required', 'numeric', 'min:0', 'max:9999999999.99'],
            'end_date' => ['nullable', 'date'],
            'description' => [
                'nullable',
                function (string $attribute, mixed $value, Closure $fail): void {
                    if (! is_string($value) && ! is_array($value)) {
                        $fail("The {$attribute} must be rich text.");
                    }
                },
            ],
        ])->validate();
    }

    /** @param array<string, mixed> $values */
    private static function validateKeys(array $values): void
    {
        if (array_diff(array_keys($values), self::Fields) !== []) {
            throw ValidationException::withMessages(['values' => 'Only project revision fields may be proposed.']);
        }
    }

    /** @param array<string, mixed> $values */
    private static function validateDates(Project $project, array $values): void
    {
        Validator::make(['start_date' => $project->start_date, 'end_date' => $values['end_date']], [
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ])->validate();
    }

    /**
     * @param  array<string, mixed>  $base
     * @param  array<string, mixed>  $candidate
     * @return array<string, mixed>
     */
    private static function differences(array $base, array $candidate): array
    {
        return array_filter($candidate, fn (mixed $value, string $field): bool => $value !== $base[$field], ARRAY_FILTER_USE_BOTH);
    }

    private static function invalidState(): never
    {
        throw ValidationException::withMessages(['status' => 'The revision is not in a valid state for this action.']);
    }

    private function appendMessage(Project $project, User $user, string $body, bool $dispatchProject = true, string $event = 'revision_updated'): void
    {
        $message = PageMessage::query()->create([
            'room' => self::room($project), 'user_id' => $user->id, 'parent_id' => $this->thread_id,
            'body' => $body, 'body_format' => 'text', 'is_agent' => false,
        ]);
        PageChatChanged::dispatch($message->room, $message->id, 'created');
        if ($dispatchProject) {
            ProjectActivity::notify($project->id, $event, entityType: 'revision', entityId: $this->id, fields: array_values(array_diff(array_keys($this->getChanges()), ['updated_at'])), reason: $event, source: 'revision');
        }
    }

    private static function room(Project $project): string
    {
        return hash('sha256', '/projects/' . $project->id);
    }

    /** @return BelongsTo<Project, $this> */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /** @return BelongsTo<User, $this> */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /** @return BelongsTo<User, $this> */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    /** @return BelongsTo<User, $this> */
    public function requestedReviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_reviewer_id');
    }

    /** @return BelongsTo<PageMessage, $this> */
    public function thread(): BelongsTo
    {
        return $this->belongsTo(PageMessage::class, 'thread_id');
    }
}
