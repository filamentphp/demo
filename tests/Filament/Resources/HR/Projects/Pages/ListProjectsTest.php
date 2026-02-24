<?php

use App\Enums\ProjectStatus;
use App\Enums\TaskPriority;
use App\Filament\Resources\HR\Projects\Pages\ListProjects;
use App\Models\HR\Department;
use App\Models\HR\Project;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\Testing\TestAction;
use Livewire\Livewire;

it('can render the list page', function () {
    $records = Project::factory()->count(3)->create();

    Livewire::test(ListProjects::class)
        ->assertOk()
        ->assertCanSeeTableRecords($records);
});

it('can change project status', function () {
    $record = Project::factory()->create(['status' => ProjectStatus::Planning]);

    Livewire::test(ListProjects::class)
        ->callAction(TestAction::make('change_status')->table($record), [
            'status' => ProjectStatus::Active,
        ]);

    $this->assertDatabaseHas(Project::class, ['id' => $record->id, 'status' => ProjectStatus::Active]);
});

it('can put active project on hold', function () {
    $record = Project::factory()->create(['status' => ProjectStatus::Active]);

    Livewire::test(ListProjects::class)
        ->callAction(TestAction::make('put_on_hold')->table($record))
        ->assertNotified();

    $this->assertDatabaseHas(Project::class, ['id' => $record->id, 'status' => ProjectStatus::OnHold]);
});

it('put on hold is hidden for non-active projects', function () {
    $record = Project::factory()->create(['status' => ProjectStatus::Planning]);

    Livewire::test(ListProjects::class)
        ->assertActionHidden(TestAction::make('put_on_hold')->table($record));
});

it('can resume a project on hold', function () {
    $record = Project::factory()->create(['status' => ProjectStatus::OnHold]);

    Livewire::test(ListProjects::class)
        ->callAction(TestAction::make('resume')->table($record))
        ->assertNotified();

    $this->assertDatabaseHas(Project::class, ['id' => $record->id, 'status' => ProjectStatus::Active]);
});

it('resume is hidden for non-on-hold projects', function () {
    $record = Project::factory()->create(['status' => ProjectStatus::Active]);

    Livewire::test(ListProjects::class)
        ->assertActionHidden(TestAction::make('resume')->table($record));
});

it('can complete a project', function () {
    $record = Project::factory()->create(['status' => ProjectStatus::Active]);

    Livewire::test(ListProjects::class)
        ->callAction(TestAction::make('complete')->table($record))
        ->assertNotified();

    $record->refresh();
    expect($record->status)->toBe(ProjectStatus::Completed);
    expect($record->end_date)->not->toBeNull();
});

it('complete is hidden for completed projects', function () {
    $record = Project::factory()->create(['status' => ProjectStatus::Completed]);

    Livewire::test(ListProjects::class)
        ->assertActionHidden(TestAction::make('complete')->table($record));
});

it('can delete a project', function () {
    $record = Project::factory()->create();

    Livewire::test(ListProjects::class)
        ->callAction(TestAction::make(DeleteAction::class)->table($record))
        ->assertNotified();
});

it('can bulk delete projects', function () {
    $records = Project::factory()->count(3)->create();

    Livewire::test(ListProjects::class)
        ->selectTableRecords($records)
        ->callAction(TestAction::make(DeleteBulkAction::class)->table()->bulk())
        ->assertNotified();
});

it('can filter by status', function () {
    $active = Project::factory()->create(['status' => ProjectStatus::Active]);
    $planning = Project::factory()->create(['status' => ProjectStatus::Planning]);

    Livewire::test(ListProjects::class)
        ->filterTable('status', ProjectStatus::Active->value)
        ->assertCanSeeTableRecords([$active])
        ->assertCanNotSeeTableRecords([$planning]);
});

it('can filter by priority', function () {
    $high = Project::factory()->create(['priority' => TaskPriority::High]);
    $low = Project::factory()->create(['priority' => TaskPriority::Low]);

    Livewire::test(ListProjects::class)
        ->filterTable('priority', TaskPriority::High->value)
        ->assertCanSeeTableRecords([$high])
        ->assertCanNotSeeTableRecords([$low]);
});

it('can filter by department', function () {
    $dept1 = Department::factory()->create();
    $dept2 = Department::factory()->create();
    $project1 = Project::factory()->create(['department_id' => $dept1->id]);
    $project2 = Project::factory()->create(['department_id' => $dept2->id]);

    Livewire::test(ListProjects::class)
        ->filterTable('department', $dept1->id)
        ->assertCanSeeTableRecords([$project1])
        ->assertCanNotSeeTableRecords([$project2]);
});

it('can filter trashed projects', function () {
    $activeProject = Project::factory()->create();
    $trashedProject = Project::factory()->create();
    $trashedProject->delete();

    Livewire::test(ListProjects::class)
        ->assertCanSeeTableRecords([$activeProject])
        ->assertCanNotSeeTableRecords([$trashedProject])
        ->filterTable('trashed', true)
        ->assertCanSeeTableRecords([$trashedProject]);
});
