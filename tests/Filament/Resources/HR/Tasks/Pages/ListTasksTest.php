<?php

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Filament\Resources\HR\Tasks\Pages\ListTasks;
use App\Models\HR\Employee;
use App\Models\HR\Project;
use App\Models\HR\Task;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ReplicateAction;
use Filament\Actions\Testing\TestAction;
use Livewire\Livewire;

it('can render the list page', function () {
    $records = Task::factory()->for(Project::factory(), 'project')->count(3)->create();

    Livewire::test(ListTasks::class)
        ->assertOk()
        ->assertCanSeeTableRecords($records);
});

it('can start a backlog task', function () {
    $record = Task::factory()->for(Project::factory(), 'project')->create([
        'status' => TaskStatus::Backlog,
    ]);

    Livewire::test(ListTasks::class)
        ->callAction(TestAction::make('start')->table($record))
        ->assertNotified();

    $this->assertDatabaseHas(Task::class, ['id' => $record->id, 'status' => TaskStatus::InProgress]);
});

it('start is hidden for in-progress tasks', function () {
    $record = Task::factory()->for(Project::factory(), 'project')->create([
        'status' => TaskStatus::InProgress,
    ]);

    Livewire::test(ListTasks::class)
        ->assertActionHidden(TestAction::make('start')->table($record));
});

it('can send task to review', function () {
    $record = Task::factory()->for(Project::factory(), 'project')->create([
        'status' => TaskStatus::InProgress,
    ]);

    Livewire::test(ListTasks::class)
        ->callAction(TestAction::make('send_to_review')->table($record))
        ->assertNotified();

    $this->assertDatabaseHas(Task::class, ['id' => $record->id, 'status' => TaskStatus::InReview]);
});

it('send to review is hidden for non-in-progress tasks', function () {
    $record = Task::factory()->for(Project::factory(), 'project')->create([
        'status' => TaskStatus::Backlog,
    ]);

    Livewire::test(ListTasks::class)
        ->assertActionHidden(TestAction::make('send_to_review')->table($record));
});

it('can complete a task', function () {
    $record = Task::factory()->for(Project::factory(), 'project')->create([
        'status' => TaskStatus::InProgress,
    ]);

    Livewire::test(ListTasks::class)
        ->callAction(TestAction::make('complete')->table($record), [
            'actual_hours' => 5,
        ])
        ->assertNotified();

    $record->refresh();
    expect($record->status)->toBe(TaskStatus::Completed);
    expect($record->completed_at)->not->toBeNull();
    expect((float) $record->actual_hours)->toBe(5.0);
});

it('complete is hidden for completed tasks', function () {
    $record = Task::factory()->for(Project::factory(), 'project')->create([
        'status' => TaskStatus::Completed,
    ]);

    Livewire::test(ListTasks::class)
        ->assertActionHidden(TestAction::make('complete')->table($record));
});

it('can assign a task', function () {
    $record = Task::factory()->for(Project::factory(), 'project')->create();
    $employee = Employee::factory()->create();

    Livewire::test(ListTasks::class)
        ->callAction(TestAction::make('assign')->table($record), [
            'assigned_to' => $employee->id,
        ]);

    $this->assertDatabaseHas(Task::class, ['id' => $record->id, 'assigned_to' => $employee->id]);
});

it('can set task priority', function () {
    $record = Task::factory()->for(Project::factory(), 'project')->create([
        'priority' => TaskPriority::Low,
    ]);

    Livewire::test(ListTasks::class)
        ->callAction(TestAction::make('set_priority')->table($record), [
            'priority' => TaskPriority::High,
        ]);

    $this->assertDatabaseHas(Task::class, ['id' => $record->id, 'priority' => TaskPriority::High]);
});

it('can replicate a task', function () {
    $record = Task::factory()->for(Project::factory(), 'project')->create();

    Livewire::test(ListTasks::class)
        ->callAction(TestAction::make(ReplicateAction::class)->table($record));

    expect(Task::where('title', $record->title)->count())->toBe(2);
});

it('can delete a task', function () {
    $record = Task::factory()->for(Project::factory(), 'project')->create();

    Livewire::test(ListTasks::class)
        ->callAction(TestAction::make(DeleteAction::class)->table($record))
        ->assertNotified();
});

it('can bulk set status', function () {
    $records = Task::factory()->for(Project::factory(), 'project')->count(3)->create([
        'status' => TaskStatus::Backlog,
    ]);

    Livewire::test(ListTasks::class)
        ->selectTableRecords($records)
        ->callAction(TestAction::make('set_status')->table()->bulk(), [
            'status' => TaskStatus::Todo,
        ])
        ->assertNotified();

    foreach ($records as $record) {
        $this->assertDatabaseHas(Task::class, ['id' => $record->id, 'status' => TaskStatus::Todo]);
    }
});

it('can bulk assign tasks', function () {
    $records = Task::factory()->for(Project::factory(), 'project')->count(3)->create();
    $employee = Employee::factory()->create();

    Livewire::test(ListTasks::class)
        ->selectTableRecords($records)
        ->callAction(TestAction::make('assign')->table()->bulk(), [
            'assigned_to' => $employee->id,
        ]);

    foreach ($records as $record) {
        $this->assertDatabaseHas(Task::class, ['id' => $record->id, 'assigned_to' => $employee->id]);
    }
});

it('can bulk delete tasks', function () {
    $records = Task::factory()->for(Project::factory(), 'project')->count(3)->create();

    Livewire::test(ListTasks::class)
        ->selectTableRecords($records)
        ->callAction(TestAction::make(DeleteBulkAction::class)->table()->bulk())
        ->assertNotified();
});

it('can filter by status', function () {
    $project = Project::factory()->create();
    $backlog = Task::factory()->create(['project_id' => $project->id, 'status' => TaskStatus::Backlog]);
    $inProgress = Task::factory()->create(['project_id' => $project->id, 'status' => TaskStatus::InProgress]);

    Livewire::test(ListTasks::class)
        ->filterTable('status', TaskStatus::Backlog->value)
        ->assertCanSeeTableRecords([$backlog])
        ->assertCanNotSeeTableRecords([$inProgress]);
});

it('can filter by priority', function () {
    $project = Project::factory()->create();
    $high = Task::factory()->create(['project_id' => $project->id, 'priority' => TaskPriority::High]);
    $low = Task::factory()->create(['project_id' => $project->id, 'priority' => TaskPriority::Low]);

    Livewire::test(ListTasks::class)
        ->filterTable('priority', TaskPriority::High->value)
        ->assertCanSeeTableRecords([$high])
        ->assertCanNotSeeTableRecords([$low]);
});

it('can filter by project', function () {
    $project1 = Project::factory()->create();
    $project2 = Project::factory()->create();
    $task1 = Task::factory()->create(['project_id' => $project1->id]);
    $task2 = Task::factory()->create(['project_id' => $project2->id]);

    Livewire::test(ListTasks::class)
        ->filterTable('project', $project1->id)
        ->assertCanSeeTableRecords([$task1])
        ->assertCanNotSeeTableRecords([$task2]);
});

it('can filter by assignee', function () {
    $project = Project::factory()->create();
    $employee1 = Employee::factory()->create();
    $employee2 = Employee::factory()->create();
    $task1 = Task::factory()->create(['project_id' => $project->id, 'assigned_to' => $employee1->id]);
    $task2 = Task::factory()->create(['project_id' => $project->id, 'assigned_to' => $employee2->id]);

    Livewire::test(ListTasks::class)
        ->filterTable('assignee', $employee1->id)
        ->assertCanSeeTableRecords([$task1])
        ->assertCanNotSeeTableRecords([$task2]);
});
