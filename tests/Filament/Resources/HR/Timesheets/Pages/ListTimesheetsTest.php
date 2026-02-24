<?php

use App\Filament\Resources\HR\Timesheets\Pages\ListTimesheets;
use App\Models\HR\Employee;
use App\Models\HR\Project;
use App\Models\HR\Timesheet;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\Testing\TestAction;
use Livewire\Livewire;

it('can render the list page', function () {
    $records = Timesheet::factory()->for(Employee::factory(), 'employee')->for(Project::factory(), 'project')->count(3)->create();

    Livewire::test(ListTimesheets::class)
        ->assertOk()
        ->assertCanSeeTableRecords($records);
});

it('can toggle billable status', function () {
    $record = Timesheet::factory()
        ->for(Employee::factory(), 'employee')
        ->for(Project::factory(), 'project')
        ->create(['is_billable' => true, 'date' => now()]);

    Livewire::test(ListTimesheets::class)
        ->callAction(TestAction::make('toggle_billable')->table($record));

    $this->assertDatabaseHas(Timesheet::class, ['id' => $record->id, 'is_billable' => false]);
});

it('can bulk mark as billable', function () {
    $records = Timesheet::factory()
        ->for(Employee::factory(), 'employee')
        ->for(Project::factory(), 'project')
        ->count(3)
        ->create(['is_billable' => false]);

    Livewire::test(ListTimesheets::class)
        ->selectTableRecords($records)
        ->callAction(TestAction::make('mark_billable')->table()->bulk());

    foreach ($records as $record) {
        $this->assertDatabaseHas(Timesheet::class, ['id' => $record->id, 'is_billable' => true]);
    }
});

it('can bulk mark as non-billable', function () {
    $records = Timesheet::factory()
        ->for(Employee::factory(), 'employee')
        ->for(Project::factory(), 'project')
        ->count(3)
        ->create(['is_billable' => true]);

    Livewire::test(ListTimesheets::class)
        ->selectTableRecords($records)
        ->callAction(TestAction::make('mark_non_billable')->table()->bulk());

    foreach ($records as $record) {
        $this->assertDatabaseHas(Timesheet::class, ['id' => $record->id, 'is_billable' => false]);
    }
});

it('can bulk delete timesheets', function () {
    $records = Timesheet::factory()
        ->for(Employee::factory(), 'employee')
        ->for(Project::factory(), 'project')
        ->count(3)
        ->create();

    Livewire::test(ListTimesheets::class)
        ->selectTableRecords($records)
        ->callAction(TestAction::make(DeleteBulkAction::class)->table()->bulk())
        ->assertNotified();
});

it('can filter by employee', function () {
    $employee1 = Employee::factory()->create();
    $employee2 = Employee::factory()->create();
    $project = Project::factory()->create();
    $ts1 = Timesheet::factory()->create(['employee_id' => $employee1->id, 'project_id' => $project->id]);
    $ts2 = Timesheet::factory()->create(['employee_id' => $employee2->id, 'project_id' => $project->id]);

    Livewire::test(ListTimesheets::class)
        ->filterTable('employee', $employee1->id)
        ->assertCanSeeTableRecords([$ts1])
        ->assertCanNotSeeTableRecords([$ts2]);
});

it('can filter by project', function () {
    $employee = Employee::factory()->create();
    $project1 = Project::factory()->create();
    $project2 = Project::factory()->create();
    $ts1 = Timesheet::factory()->create(['employee_id' => $employee->id, 'project_id' => $project1->id]);
    $ts2 = Timesheet::factory()->create(['employee_id' => $employee->id, 'project_id' => $project2->id]);

    Livewire::test(ListTimesheets::class)
        ->filterTable('project', $project1->id)
        ->assertCanSeeTableRecords([$ts1])
        ->assertCanNotSeeTableRecords([$ts2]);
});

it('can filter by billable status', function () {
    $employee = Employee::factory()->create();
    $project = Project::factory()->create();
    $billable = Timesheet::factory()->create(['employee_id' => $employee->id, 'project_id' => $project->id, 'is_billable' => true]);
    $nonBillable = Timesheet::factory()->create(['employee_id' => $employee->id, 'project_id' => $project->id, 'is_billable' => false]);

    Livewire::test(ListTimesheets::class)
        ->filterTable('is_billable', true)
        ->assertCanSeeTableRecords([$billable])
        ->assertCanNotSeeTableRecords([$nonBillable]);
});

it('can filter by date range', function () {
    $employee = Employee::factory()->create();
    $project = Project::factory()->create();
    $oldTs = Timesheet::factory()->create(['employee_id' => $employee->id, 'project_id' => $project->id, 'date' => now()->subMonths(3)]);
    $recentTs = Timesheet::factory()->create(['employee_id' => $employee->id, 'project_id' => $project->id, 'date' => now()->subDay()]);

    Livewire::test(ListTimesheets::class)
        ->filterTable('date_range', [
            'from' => now()->subWeek()->toDateString(),
            'until' => now()->toDateString(),
        ])
        ->assertCanSeeTableRecords([$recentTs])
        ->assertCanNotSeeTableRecords([$oldTs]);
});
