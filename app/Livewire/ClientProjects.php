<?php

namespace App\Livewire;

use App\Enums\ProjectStatus;
use App\Enums\TaskPriority;
use App\Models\HR\Project;
use Illuminate\Support\Facades\Context;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;

class ClientProjects extends Component
{
    #[Url]
    public ?int $projectId = null;

    /** @var array<string, mixed> */
    public array $data = [];

    public string $taskTitle = '';

    public string $message = '';

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

        $this->data = [
            'name' => $project->name,
            'status' => $project->status->value,
            'priority' => $project->priority->value,
            'budget' => $project->budget,
            'spent' => $project->spent,
        ];
        $this->reset('taskTitle', 'message');
        $this->resetValidation();
    }

    public function save(): void
    {
        $validated = $this->validate([
            'data.name' => ['required', 'string', 'max:255'],
            'data.status' => ['required', Rule::enum(ProjectStatus::class)],
            'data.priority' => ['required', Rule::enum(TaskPriority::class)],
            'data.budget' => ['nullable', 'numeric', 'min:0', 'max:9999999999.99'],
            'data.spent' => ['required', 'numeric', 'min:0', 'max:9999999999.99'],
        ]);

        $project = Project::query()->findOrFail($this->projectId);
        Context::scope(
            fn () => $project->update($validated['data']),
            hidden: ['project_history_interface' => 'client_portal'],
        );

        $this->message = 'Project saved.';
    }

    public function addTask(): void
    {
        $this->validate(['taskTitle' => ['required', 'string', 'max:255']]);

        $project = Project::query()->findOrFail($this->projectId);
        Context::scope(
            fn () => $project->tasks()->create(['title' => $this->taskTitle]),
            hidden: ['project_history_interface' => 'client_portal'],
        );

        $this->reset('taskTitle');
        $this->message = 'Task added.';
    }

    public function render(): View
    {
        return view('livewire.client-projects', [
            'projects' => Project::query()->orderBy('name')->get(['id', 'name']),
        ])->layout('layouts.app');
    }
}
