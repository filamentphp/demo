<?php

namespace App\Filament\Resources\HR\Projects\Pages;

use App\Filament\Resources\HR\Projects\ProjectResource;
use App\Models\HR\Department;
use App\Models\HR\Project;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;

class EditProject extends EditRecord
{
    protected static string $resource = ProjectResource::class;

    protected ?bool $hasDatabaseTransactions = true;

    /** @var array<string, mixed> */
    #[Locked]
    public array $savedValues = [];

    /** @var array<string, array{original: mixed, local: mixed, remote: mixed, by: string}> */
    #[Locked]
    public array $conflicts = [];

    /** @var array<string, string> */
    public array $conflictChoices = [];

    #[Locked]
    public int $loadedDescriptionVersion = 0;

    protected function afterFill(): void
    {
        $this->savedValues = $this->getRecord()->attributesToArray();
        $this->loadedDescriptionVersion = (int) $this->getRecord()->getAttribute('description_version');
    }

    /** @param array{projectId: int} $event */
    #[On('echo-private:projects,ProjectChanged')]
    public function projectChanged(array $event): void
    {
        if ($event['projectId'] !== $this->getRecord()->getKey()) {
            $this->skipRender();

            return;
        }

        $this->synchronizeProject();
    }

    protected function beforeValidate(): void
    {
        $this->synchronizeProject();

        if ($this->loadedDescriptionVersion !== (int) $this->getRecord()->getAttribute('description_version')) {
            $this->notifyReplacedDescription();
            $this->halt();
        }

        if ($this->conflicts !== []) {
            $this->notifyConflicts();
            $this->dispatch('open-modal', id: 'project-conflicts');
            $this->halt();
        }
    }

    protected function afterSave(): void
    {
        $this->savedValues = $this->getRecord()->attributesToArray();
    }

    public function refreshFormData(array $statePaths): void
    {
        if (in_array('plan', $statePaths, true)) {
            $this->data['plan'] = $this->getRecord()->getAttribute('plan');
        }

        parent::refreshFormData($statePaths);
    }

    protected function synchronizeProject(): void
    {
        $previousConflicts = $this->conflicts;
        $this->record = $project = Project::withTrashed()->whereKey($this->getRecord()->getKey())->lockForUpdate()->firstOrFail();
        $remote = $project->attributesToArray();
        $refresh = [];

        if (($this->savedValues['description_version'] ?? 0) !== ($remote['description_version'] ?? 0)) {
            $this->notifyReplacedDescription();
        }

        foreach (['name', 'slug', 'department_id', 'status', 'priority', 'color', 'start_date', 'end_date', 'budget', 'spent', 'estimated_hours', 'actual_hours', 'plan'] as $field) {
            $original = $this->savedValues[$field] ?? null;
            $local = $this->data[$field] ?? null;
            $latest = $remote[$field] ?? null;
            $remoteChanged = $this->normalizeValue($field, $latest) !== $this->normalizeValue($field, $original);
            $localChanged = $this->normalizeValue($field, $local) !== $this->normalizeValue($field, $original);
            $equal = $this->normalizeValue($field, $local) === $this->normalizeValue($field, $latest);

            if ($equal || ($remoteChanged && ! $localChanged && ! isset($this->conflicts[$field]))) {
                if (! $equal) {
                    $refresh[] = $field;
                }

                unset($this->conflicts[$field], $this->conflictChoices[$field]);
            } elseif (($remoteChanged && $localChanged) || isset($this->conflicts[$field])) {
                if ($remoteChanged || (($this->conflicts[$field]['local'] ?? null) !== $local)) {
                    unset($this->conflictChoices[$field]);
                }

                $activity = $project->activities()->whereNotNull('changes->' . $field)->latest('id')->first();
                $this->conflicts[$field] = [
                    'original' => $this->conflicts[$field]['original'] ?? $original,
                    'local' => $local,
                    'remote' => $latest,
                    'by' => $activity ? $activity->actor_name . ' · ' . match ($activity->interface) {
                        'client_portal' => 'Client portal',
                        'panel' => 'Panel',
                        default => 'System',
                    } . ' · ' . $activity->created_at?->format('H:i:s') : 'Another saved change',
                ];
            }
        }

        if ($refresh !== []) {
            $this->refreshFormData($refresh);
        }

        $this->savedValues = $remote;

        if ($this->conflicts !== $previousConflicts) {
            if ($this->conflicts !== []) {
                $this->notifyConflicts();
            } else {
                $this->dispatch('notificationClosed', id: 'project-conflicts');
                $this->dispatch('close-modal', id: 'project-conflicts');
            }
        }
    }

    public function resolveConflicts(): void
    {
        $this->synchronizeProject();

        $this->validate(collect($this->conflicts)->mapWithKeys(fn (array $conflict, string $field): array => [
            'conflictChoices.' . $field => ['required', 'in:local,remote'],
        ])->all(), ['required' => 'Choose which value to keep.']);

        $remoteFields = [];

        foreach ($this->conflicts as $field => $conflict) {
            if ($this->conflictChoices[$field] === 'remote') {
                $remoteFields[] = $field;
            }
        }

        $this->refreshFormData($remoteFields);
        $this->conflicts = [];
        $this->conflictChoices = [];
        $this->dispatch('notificationClosed', id: 'project-conflicts');
        $this->dispatch('close-modal', id: 'project-conflicts');
    }

    protected function notifyConflicts(): void
    {
        Notification::make('project-conflicts')
            ->warning()
            ->title('Review conflicting changes')
            ->body(count($this->conflicts) . ' ' . str('field')->plural(count($this->conflicts)) . ' changed while you were editing. Your draft is safe.')
            ->persistent()
            ->actions([
                Action::make('review')->label('Review changes')->button()->dispatch('open-modal', ['id' => 'project-conflicts']),
            ])
            ->send();
    }

    protected function notifyReplacedDescription(): void
    {
        Notification::make('project-description-replaced')->warning()->title('An approved revision replaced the description')
            ->body('Your open draft has not been discarded. Copy any unsaved work, then reload this page before saving to use the approved description.')
            ->persistent()->send();
    }

    protected function normalizeValue(string $field, mixed $value): mixed
    {
        if ($field === 'plan') {
            return array_values($value ?? []);
        }

        if (($value === null) || ($value === '')) {
            return null;
        }

        return match ($field) {
            'budget', 'spent' => number_format((float) $value, 2, '.', ''),
            'estimated_hours', 'actual_hours' => number_format((float) $value, 1, '.', ''),
            'start_date', 'end_date' => substr((string) $value, 0, 10),
            default => (string) $value,
        };
    }

    public function conflictValue(string $field, mixed $value): string
    {
        if (($value === null) || ($value === '') || ($value === [])) {
            return 'Not set';
        }

        return match ($field) {
            'budget', 'spent' => '$' . number_format((float) $value, 2),
            'department_id' => Department::query()->whereKey($value)->value('name') ?? (string) $value,
            'status', 'priority' => str((string) $value)->replace('_', ' ')->ucfirst()->toString(),
            'plan' => json_encode(array_values($value), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR),
            default => (string) $this->normalizeValue($field, $value),
        };
    }

    public function getFooter(): ?View
    {
        return view('filament.projects.conflicts');
    }

    protected function getActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            RestoreAction::make(),
            ForceDeleteAction::make(),
        ];
    }
}
