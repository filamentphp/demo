<?php

namespace App\Ai;

use App\Filament\Resources\HR\Projects\Pages\EditProject;
use App\Filament\Resources\HR\Projects\Pages\ViewProject;
use App\Filament\Resources\HR\Projects\ProjectResource;
use App\Models\HR\Project;
use App\Models\HR\ProjectRevision;
use App\Models\PageMessage;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\Entry;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\Page;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Schema;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Traversable;

class PageInteraction
{
    /** @var array{name: string, budget: string, end_date: ?string, description: string}|null */
    protected ?array $inspectedProject = null;

    /** @var array<int, array<string, mixed>> */
    protected array $inspectedTasks = [];

    public function __construct(public Page $page, public PageMessage $request) {}

    public function room(): string
    {
        $path = str($this->page->getResourceUrl($this->page::getResourcePageName(), isAbsolute: false))->before('?')->toString();
        if (preg_match('#^/projects/(\d+)/edit$#', $path, $matches)) {
            $path = '/projects/' . $matches[1];
        }

        return hash('sha256', $path);
    }

    /** @return array<string, mixed> */
    public function inspect(): array
    {
        $state = [
            'resource' => $this->page::getResource()::getModelLabel(),
            'page_type' => $this->page::getResourcePageName(),
            'title' => $this->value($this->page->getTitle()),
            'record_id' => $this->record()?->getKey(),
            'header_actions' => array_keys($this->actions()),
            'validation_errors' => $this->page->getErrorBag()->toArray(),
        ];

        if ($this->page instanceof EditRecord || $this->page instanceof CreateRecord) {
            $state['form'] = $this->describeFields($this->page->getSchema('form'));
            $state['form_is_unsaved_draft'] = true;
        } elseif ($this->page instanceof ViewRecord) {
            foreach ($this->page->getSchema('infolist')?->getFlatComponents() ?? [] as $component) {
                if ($component instanceof Entry && ! $component->isHidden()) {
                    $state['record'][$component->getName()] = $this->value($component->getState());
                }
            }
        }

        if ($this->page instanceof ListRecords) {
            $table = $this->page->getTable();
            $state['search'] = $this->page->tableSearch;
            $state['sort'] = $this->page->tableSort;
            $state['filters'] = $this->describeFields($this->page->getTableFiltersForm());
            $state['sortable_columns'] = [];
            foreach ($table->getColumns() as $column) {
                if (! $column->isHidden() && $column->isSortable()) {
                    $state['sortable_columns'][] = $column->getName();
                }
            }
            foreach ($this->rows() as $record) {
                $row = ['key' => $this->page->getTableRecordKey($record)];
                foreach ($table->getColumns() as $column) {
                    if (! $column->isHidden() && ! $column->isToggledHidden()) {
                        $row[$column->getName()] = $this->value((clone $column)->record($record)->getState());
                    }
                }
                $row['actions'] = array_keys($this->actions($record));
                $state['rows'][] = $row;
            }
        }

        if ($project = $this->project()?->fresh()) {
            $state['saved_project'] = $this->inspectedProject = ProjectRevision::snapshot($project);
            $state['project_owner'] = $project->owner?->only(['id', 'name']);
            $state['current_tasks'] = $project->tasks()->orderBy('id')->get(['id', 'title', 'status', 'due_date'])->map(fn (Model $task): array => [
                'id' => $task->getKey(),
                'title' => $task->getAttribute('title'),
                'status' => $this->value($task->getAttribute('status')),
                'due_date' => $task->getAttribute('due_date')?->format('Y-m-d'),
            ])->all();
            $this->inspectedTasks = [];
            foreach ($state['current_tasks'] as $task) {
                $this->inspectedTasks[$task['id']] = ['title' => $task['title'], 'status' => $task['status'], 'due_date' => $task['due_date']];
            }
            $state['revision_capabilities'] = [
                'agent' => ['propose_revision', 'read_revisions'],
                'proposal_fields' => ProjectRevision::Fields,
                'task_proposal_fields' => ['title', 'status', 'due_date'],
                'review' => 'Human-only. The author must request a named reviewer before approval. Agent cannot assign owners or reviewers, approve, apply, reject, withdraw, or request changes.',
                'source' => 'A proposal is authored by the requesting human and attributed to Agent through the source message.',
            ];
        }

        return $state;
    }

    /** @param array<string, mixed> $input */
    public function operate(string $operation, array $input): mixed
    {
        return match ($operation) {
            'inspect' => $this->inspect(),
            'update_form' => $this->updateForm($input),
            'configure_table' => $this->configureTable($input),
            'run_action' => $this->runAction($input),
            'save_form' => $this->saveForm(),
            'propose_revision' => $this->proposeRevision($input),
            'read_revisions' => $this->revisions(),
            'read_history' => $this->history(),
            'read_chat' => $this->discussion(),
            default => throw ValidationException::withMessages(['operation' => 'Unknown operation.']),
        };
    }

    /** @return array<string, Field> */
    protected function fields(?Schema $schema): array
    {
        $fields = [];
        foreach ($schema?->getFlatFields() ?? [] as $field) {
            if ($field instanceof Hidden || $field instanceof FileUpload || $field->isHidden() || ($field instanceof TextInput && $field->isPassword())) {
                continue;
            }
            $fields[$field->getStatePath()] = $field;
        }

        return $fields;
    }

    protected function editable(Field $field): bool
    {
        return ! $field->isDisabled() && ! (method_exists($field, 'isReadOnly') && $field->isReadOnly());
    }

    /** @return array<string, mixed> */
    protected function describeFields(?Schema $schema): array
    {
        $result = [];
        foreach ($this->fields($schema) as $path => $field) {
            $result[$path] = [
                'label' => $this->value($field->getLabel()),
                'type' => class_basename($field),
                'value' => $this->value($field->getState()),
                'editable' => $this->editable($field),
            ];
            if (method_exists($field, 'getOptions')) {
                $result[$path]['options'] = array_slice($field->getOptions(), 0, 50, true);
            }
        }

        return $result;
    }

    /** @param array<string, mixed> $input */
    protected function updateForm(array $input): string
    {
        if ($this->project() instanceof Project) {
            throw ValidationException::withMessages(['operation' => 'Project forms cannot be changed by Agent. Use propose_revision to create a durable proposal for human review.']);
        }
        if (! ($this->page instanceof EditRecord || $this->page instanceof CreateRecord)) {
            throw ValidationException::withMessages(['form' => 'This page has no editable form.']);
        }
        $fields = $this->fields($this->page->getSchema('form'));
        $this->setFields($fields, $input);

        return 'Updated the form draft. Not saved. Inspect to verify the current values.';
    }

    /**
     * @param  array<string, Field>  $fields
     * @param  array<string, mixed>  $values
     */
    protected function setFields(array $fields, array $values): void
    {
        foreach ($values as $path => $value) {
            if (! isset($fields[$path]) || ! $this->editable($fields[$path])) {
                throw ValidationException::withMessages([$path => 'Field is not editable. Use an exact field path from inspect.']);
            }
        }
        foreach ($values as $path => $value) {
            $fields[$path]->state($value);
            $fields[$path]->callAfterStateUpdated();
        }
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array<string, mixed>
     */
    protected function configureTable(array $input): array
    {
        $page = $this->page;
        if (! $page instanceof ListRecords) {
            throw ValidationException::withMessages(['table' => 'This is not a table page.']);
        }
        validator($input, ['search' => 'sometimes|nullable|string|max:255', 'sort' => 'sometimes|string', 'direction' => 'sometimes|in:asc,desc', 'filters' => 'sometimes|array'])->validate();
        if (isset($input['sort'])) {
            $column = $page->getTable()->getColumn($input['sort']);
            if (! $column || $column->isHidden() || ! $column->isSortable()) {
                throw ValidationException::withMessages(['sort' => 'Choose a sortable column from inspect.']);
            }
            $page->sortTable($input['sort'], $input['direction'] ?? 'asc');
        }
        if (array_key_exists('search', $input)) {
            $page->tableSearch = $input['search'] ?? '';
            $page->updatedTableSearch();
        }
        if (isset($input['filters'])) {
            $this->setFields($this->fields($page->getTableFiltersForm()), $input['filters']);
            if ($page->getTable()->hasDeferredFilters()) {
                $page->applyTableFilters();
            } else {
                $page->updatedTableFilters();
            }
        }
        $page->flushCachedTableRecords();

        return $this->inspect();
    }

    /** @return array<string, Action> */
    protected function actions(?Model $record = null): array
    {
        $actions = $record && $this->page instanceof ListRecords
            ? $this->page->getTable()->getRecordActions()
            : $this->page->getCachedHeaderActions();
        $result = [];
        foreach ($actions as $action) {
            if ($record) {
                $action->record($record);
            }
            foreach ($action instanceof ActionGroup ? $action->getFlatActions() : [$action] as $item) {
                $item = clone $item;
                if ($record) {
                    $item->record($record);
                }
                if (! $item->isHidden() && ! $item->isDisabled() && $item->isAuthorized() && ! $item->getUrl()) {
                    $result[$item->getName()] = $item;
                }
            }
        }

        return $result;
    }

    /** @param array<string, mixed> $input */
    protected function runAction(array $input): string
    {
        validator($input, ['name' => 'required|string', 'record_key' => 'sometimes|nullable|string'])->validate();
        if ($this->project() instanceof Project && preg_match('/(?:approve|apply).*revision|revision.*(?:approve|apply)/i', $input['name'])) {
            throw ValidationException::withMessages(['name' => 'Agent cannot approve or apply project revisions. A human reviewer must use the revision controls.']);
        }
        $page = $this->page;
        $record = isset($input['record_key']) && $page instanceof ListRecords ? $this->rows()->first(fn (Model $record): bool => $page->getTableRecordKey($record) === $input['record_key']) : null;
        if (isset($input['record_key']) && ! $record) {
            throw ValidationException::withMessages(['record_key' => 'Choose a row in the current table results.']);
        }
        if (! isset($this->actions($record)[$input['name']])) {
            throw ValidationException::withMessages(['name' => 'This action is not available.']);
        }
        if ($this->page->mountedActions !== []) {
            return 'An action modal is already open. Ask the user to finish or cancel it first.';
        }
        $this->page->mountAction($input['name'], context: $record ? ['table' => true, 'recordKey' => $input['record_key']] : []);

        return $this->page->getMountedAction() ? 'Opened the action modal. The user must confirm or complete it; the action has NOT executed.' : 'Invoked the action. Inspect the page to verify its result.';
    }

    protected function saveForm(): string
    {
        if ($this->project() instanceof Project) {
            throw ValidationException::withMessages(['operation' => 'Agent cannot save a Project form. Use propose_revision to create a durable proposal for human review.']);
        }
        if ($this->page instanceof EditRecord) {
            $this->page->save();
        } elseif ($this->page instanceof CreateRecord) {
            $this->page->create();
        } else {
            throw ValidationException::withMessages(['form' => 'This page has no editable form.']);
        }

        if ($this->page instanceof EditProject && $this->page->conflicts !== []) {
            return 'Not saved: unresolved conflicts remain. Ask the user to resolve them in the conflict modal before saving again.';
        }

        return 'Invoked the native Save/Create flow. Validation, conflicts, and confirmation UI still apply. Do not claim success if errors or conflicts remain.';
    }

    protected function record(): ?Model
    {
        return $this->page instanceof EditRecord || $this->page instanceof ViewRecord ? $this->page->getRecord() : null;
    }

    protected function project(): ?Project
    {
        if (! ($this->page instanceof EditProject || $this->page instanceof ViewProject)) {
            return null;
        }

        $record = $this->record();

        return $record instanceof Project ? $record : null;
    }

    /** @param array<string, mixed> $input
     * @return array<string, mixed>
     */
    protected function proposeRevision(array $input): array
    {
        $project = $this->authorizedProject();
        $author = auth()->user();
        abort_unless($author !== null, 403);
        validator($input, [
            'values' => ['sometimes', 'array'],
            'values.*' => ['nullable'],
            'tasks' => ['sometimes', 'array'],
            'tasks.*' => ['array'],
            'tasks.*.title' => ['sometimes', 'string', 'max:255'],
            'tasks.*.status' => ['sometimes', 'string'],
            'tasks.*.due_date' => ['sometimes', 'nullable', 'date'],
            'reason' => ['required', 'string', 'max:5000'],
        ])->validate();

        $values = $input['values'] ?? [];
        if (array_diff(array_keys($values), ProjectRevision::Fields) !== []) {
            throw ValidationException::withMessages(['values' => 'Only name, budget, end_date, and description may be proposed.']);
        }
        $taskValues = $input['tasks'] ?? [];
        $allowedTaskFields = ['title', 'status', 'due_date'];
        foreach ($taskValues as $taskId => $taskChanges) {
            if (array_diff(array_keys($taskChanges), $allowedTaskFields) !== []) {
                throw ValidationException::withMessages(["tasks.{$taskId}" => 'Only title, status, and due_date may be proposed for a task.']);
            }
        }
        $taskIds = array_map('intval', array_keys($taskValues));
        if ($project->tasks()->whereKey($taskIds)->count() !== count(array_unique($taskIds))) {
            throw ValidationException::withMessages(['tasks' => 'Every task must belong to the current project. Use task IDs from inspect.']);
        }

        if ($this->inspectedProject === null) {
            $this->inspect();
        }

        $revision = ProjectRevision::propose(
            $project,
            $author,
            $values,
            $input['reason'],
            expectedBase: $this->inspectedProject,
            taskValues: $taskValues,
            sourceMessage: $this->request,
            expectedTasks: $this->inspectedTasks,
        );

        return ['created' => true, 'revision_id' => $revision->getKey(), 'status' => $revision->status];
    }

    /** @return array<int, array<string, mixed>> */
    protected function revisions(): array
    {
        $project = $this->authorizedProject();

        return ProjectRevision::query()->where('project_id', $project->getKey())->with(['author:id,name', 'reviewer:id,name', 'requestedReviewer:id,name'])->latest('id')->limit(30)->get()->map(fn (ProjectRevision $revision): array => [
            'id' => $revision->id,
            'status' => $revision->status,
            'version' => $revision->version,
            'reason' => $revision->reason,
            'base_values' => $revision->base_values,
            'proposed_values' => $revision->proposed_values,
            'base_tasks' => $revision->base_tasks,
            'proposed_tasks' => $revision->proposed_tasks,
            'author' => $revision->author->name,
            'reviewer' => $revision->reviewer?->name,
            'requested_reviewer' => $revision->requestedReviewer?->only(['id', 'name']),
            'next_step' => $revision->nextStep(),
            'next_step_owner' => $revision->nextStepOwner()?->only(['id', 'name']),
            'thread_id' => $revision->thread_id,
            'created_at' => $revision->created_at?->toIso8601String(),
        ])->all();
    }

    protected function authorizedProject(): Project
    {
        $project = $this->project();
        if (! $project || ! auth()->check() || $this->request->user_id !== auth()->id() || ! ProjectResource::canView($project) || ! ProjectResource::canEdit($project)) {
            throw ValidationException::withMessages(['operation' => 'Project revisions are available only on the authorized current Project view or edit page for the requesting user.']);
        }

        return $project;
    }

    /** @return Collection<int, Model> */
    protected function rows(): Collection
    {
        $records = collect();
        if ($this->page instanceof ListRecords) {
            $visible = $this->page->getTableRecords();
            foreach ($visible instanceof Traversable ? $visible : [] as $record) {
                $records->push($record);
                if ($records->count() === 20) {
                    break;
                }
            }
        }

        return $records;
    }

    /** @return array<mixed> */
    public function history(): array
    {
        $record = $this->record();

        return $record instanceof Project ? $record->activities()->latest('id')->limit(30)->get(['event', 'subject', 'actor_name', 'interface', 'changes', 'change_envelope', 'created_at'])->toArray() : ['No recorded history is available for this resource.'];
    }

    /** @return array<mixed> */
    public function discussion(): array
    {
        return PageMessage::query()->where('room', $this->request->room)->whereNotNull('body')
            ->where(fn ($query) => $query->whereNull('agent_status')->orWhere('agent_status', 'completed'))
            ->with('user')->latest('id')->limit(50)->get()->reverse()->map(fn (PageMessage $message): array => [
                'id' => $message->id, 'thread' => $message->parent_id, 'author' => $message->authorName(),
                'text' => mb_substr(html_entity_decode(strip_tags($message->contentHtml())), 0, 2000),
                'when' => $message->created_at?->toIso8601String(),
                ...($this->record() instanceof Project ? [
                    'field' => $message->project_field,
                    'resolved' => $message->resolved_at !== null,
                    'url' => '/projects/' . $this->record()->getKey() . '?chat=' . ($message->parent_id ?? $message->id),
                ] : []),
            ])->values()->all();
    }

    protected function value(mixed $value): mixed
    {
        if ($value instanceof Htmlable) {
            $value = $value->toHtml();
        }
        if ($value instanceof BackedEnum) {
            return $value->value;
        }
        if (is_string($value)) {
            return mb_substr(strip_tags($value), 0, 3000);
        }
        if (is_array($value)) {
            return array_map($this->value(...), array_slice($value, 0, 30, true));
        }

        return $value;
    }
}
