<?php

namespace App\Livewire;

use App\Enums\ProjectStatus;
use App\Enums\TaskPriority;
use App\Models\HR\Project;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Context;
use Illuminate\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;

/**
 * @property-read Schema $selectorForm
 * @property-read Schema $projectForm
 * @property-read Schema $taskForm
 */
class ClientProjects extends Component implements HasSchemas
{
    use InteractsWithSchemas;

    #[Url]
    public ?int $projectId = null;

    /** @var array<string, mixed> */
    public array $data = [];

    public string $taskTitle = '';

    public string $message = '';

    public function selectorForm(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('projectId')
                    ->label('Select project')
                    ->options(fn (): array => Project::query()->orderBy('name')->pluck('name', 'id')->all())
                    ->required()
                    ->live(),
            ]);
    }

    public function projectForm(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Project name')
                    ->required()
                    ->maxLength(255),
                Select::make('status')
                    ->options(ProjectStatus::class)
                    ->required(),
                Select::make('priority')
                    ->options(TaskPriority::class)
                    ->required(),
                TextInput::make('budget')
                    ->label('Budget (USD)')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(9999999999.99),
                TextInput::make('spent')
                    ->label('Spent (USD)')
                    ->numeric()
                    ->required()
                    ->minValue(0)
                    ->maxValue(9999999999.99),
            ])
            ->columns(2)
            ->statePath('data');
    }

    public function taskForm(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('taskTitle')
                    ->label('Task title')
                    ->placeholder('Review the latest designs')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function boot(): void
    {
        abort_unless(auth()->check(), 403);
    }

    public function mount(): void
    {
        $this->projectId ??= Project::query()->orderBy('name')->value('id');
        $this->updatedProjectId();
    }

    public function updatedProjectId(): void
    {
        $project = Project::query()->findOrFail($this->projectId);

        $this->projectForm->fill([
            'name' => $project->name,
            'status' => $project->status->value,
            'priority' => $project->priority->value,
            'budget' => $project->budget,
            'spent' => $project->spent,
        ]);
        $this->taskForm->fill(['taskTitle' => '']);
        $this->reset('message');
        $this->resetValidation();
    }

    public function save(): void
    {
        $data = $this->projectForm->getState();

        $project = Project::query()->findOrFail($this->projectId);
        Context::scope(
            fn () => $project->update($data),
            hidden: ['project_history_interface' => 'client_portal'],
        );

        $this->message = 'Project saved.';
    }

    public function addTask(): void
    {
        $data = $this->taskForm->getState();

        $project = Project::query()->findOrFail($this->projectId);
        Context::scope(
            fn () => $project->tasks()->create(['title' => $data['taskTitle']]),
            hidden: ['project_history_interface' => 'client_portal'],
        );

        $this->taskForm->fill(['taskTitle' => '']);
        $this->message = 'Task added.';
    }

    public function render(): View
    {
        return view('livewire.client-projects')->layout('layouts.app');
    }
}
