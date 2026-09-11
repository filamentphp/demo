<?php

use App\Ai\PageInteraction;
use App\Enums\TaskStatus;
use App\Filament\Resources\HR\Projects\Pages\EditProject;
use App\Filament\Resources\HR\Projects\Pages\ListProjects;
use App\Filament\Resources\HR\Projects\Pages\ViewProject;
use App\Models\HR\Project;
use App\Models\HR\ProjectRevision;
use App\Models\HR\Task;
use App\Models\PageMessage;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Livewire\Livewire;

function projectInteraction(Project $project, string $page = 'view', ?PageMessage $request = null): PageInteraction
{
    $pageClass = $page === 'edit' ? EditProject::class : ViewProject::class;
    $request ??= PageMessage::query()->create([
        'room' => hash('sha256', '/projects/' . $project->id),
        'user_id' => auth()->id(),
        'body' => 'Please revise this project.',
        'body_format' => 'text',
    ]);

    return new PageInteraction(Livewire::test($pageClass, ['record' => $project->id])->instance(), $request);
}

it('inspects saved project state, current tasks, and durable revision capabilities', function (): void {
    $project = Project::factory()->create(['name' => 'Saved project', 'budget' => 1200]);
    $task = Task::factory()->for($project)->create(['title' => 'Saved task', 'status' => TaskStatus::InProgress]);

    $state = projectInteraction($project, 'edit')->inspect();

    expect($state['form_is_unsaved_draft'])->toBeTrue()
        ->and($state['saved_project']['name'])->toBe('Saved project')
        ->and($state['saved_project']['budget'])->toBe('1200.00')
        ->and($state['current_tasks'][0]['id'])->toBe($task->id)
        ->and($state['current_tasks'][0]['title'])->toBe('Saved task')
        ->and($state['revision_capabilities']['agent'])->toBe(['propose_revision', 'read_revisions'])
        ->and($state['revision_capabilities']['review'])->toContain('Human-only');
});

it('creates a durable attributed revision without changing the project or task', function (): void {
    $project = Project::factory()->create(['name' => 'Original']);
    $task = Task::factory()->for($project)->create(['title' => 'Original task']);
    $request = PageMessage::query()->create([
        'room' => hash('sha256', '/projects/' . $project->id),
        'user_id' => auth()->id(),
        'body' => 'Update the plan.',
        'body_format' => 'text',
    ]);

    $result = projectInteraction($project, request: $request)->operate('propose_revision', [
        'values' => ['name' => 'Proposed project'],
        'tasks' => [(string) $task->id => ['title' => 'Proposed task']],
        'reason' => 'Requested in project chat',
    ]);

    $revision = ProjectRevision::query()->sole();
    expect($result['revision_id'])->toBe($revision->id)
        ->and($revision->author_id)->toBe(auth()->id())
        ->and($revision->requested_reviewer_id)->toBeNull()
        ->and($project->refresh()->name)->toBe('Original')
        ->and($task->refresh()->title)->toBe('Original task')
        ->and(projectInteraction($project)->operate('read_revisions', [])[0]['reason'])->toBe('Requested in project chat');
});

it('requires the authenticated user to own the source request on a current project page', function (): void {
    $project = Project::factory()->create();
    $otherUser = User::factory()->create();
    $request = PageMessage::query()->create([
        'room' => hash('sha256', '/projects/' . $project->id),
        'user_id' => $otherUser->id,
        'body' => 'Unauthorized request',
        'body_format' => 'text',
    ]);

    expect(fn () => projectInteraction($project, request: $request)->operate('read_revisions', []))
        ->toThrow(ValidationException::class);

    $listInteraction = new PageInteraction(Livewire::test(ListProjects::class)->instance(), $request);
    expect(fn () => $listInteraction->operate('propose_revision', ['values' => ['name' => 'No'], 'reason' => 'No']))
        ->toThrow(ValidationException::class);
});

it('blocks direct project form mutation and saving with revision guidance', function (): void {
    $project = Project::factory()->create(['name' => 'Original']);
    $interaction = projectInteraction($project, 'edit');

    foreach (['update_form' => ['data.name' => 'Changed'], 'save_form' => []] as $operation => $input) {
        try {
            $interaction->operate($operation, $input);
            test()->fail("{$operation} should have been rejected.");
        } catch (ValidationException $exception) {
            expect(json_encode($exception->errors()))->toContain('propose_revision');
        }
    }

    expect($project->refresh()->name)->toBe('Original');
});

it('rejects a proposal when saved values changed while the agent was thinking', function (bool $changeTask): void {
    $project = Project::factory()->create(['name' => 'Original']);
    $task = Task::factory()->for($project)->create(['title' => 'Original task']);
    $interaction = projectInteraction($project);
    $interaction->inspect();
    if ($changeTask) {
        $task->update(['title' => 'Another persons task change']);
    } else {
        $project->update(['name' => 'Another persons project change']);
    }
    expect(fn () => $interaction->operate('propose_revision', [
        'values' => ['name' => 'Agent proposal'], 'tasks' => [$task->id => ['title' => 'Agent task']], 'reason' => 'Requested change',
    ]))->toThrow(ValidationException::class);
    expect(ProjectRevision::query()->count())->toBe(0);
})->with([false, true]);
