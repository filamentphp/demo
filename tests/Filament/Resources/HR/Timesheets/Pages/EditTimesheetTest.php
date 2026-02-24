<?php

use App\Filament\Resources\HR\Timesheets\Pages\EditTimesheet;
use App\Models\HR\Employee;
use App\Models\HR\Project;
use App\Models\HR\Timesheet;
use Illuminate\Support\Str;
use Livewire\Livewire;

it('can render the edit page', function () {
    $employee = Employee::factory()->create();
    $project = Project::factory()->create();
    $record = Timesheet::factory()->create(['employee_id' => $employee->id, 'project_id' => $project->id]);

    Livewire::test(EditTimesheet::class, ['record' => $record->getRouteKey()])
        ->assertOk();
});

it('can update a record', function () {
    $employee = Employee::factory()->create();
    $project = Project::factory()->create();
    $record = Timesheet::factory()->create(['employee_id' => $employee->id, 'project_id' => $project->id]);

    Livewire::test(EditTimesheet::class, ['record' => $record->getRouteKey()])
        ->fillForm([
            'hours' => 6,
            'hourly_rate' => 100.00,
        ])
        ->call('save')
        ->assertNotified();

    $this->assertDatabaseHas(Timesheet::class, [
        'id' => $record->id,
    ]);
});

it('recalculates total_cost on save', function () {
    $employee = Employee::factory()->create();
    $project = Project::factory()->create();
    $record = Timesheet::factory()->create([
        'employee_id' => $employee->id,
        'project_id' => $project->id,
        'hours' => 8,
        'hourly_rate' => 75.00,
        'total_cost' => 600.00,
    ]);

    Livewire::test(EditTimesheet::class, ['record' => $record->getRouteKey()])
        ->fillForm([
            'hours' => 4,
            'hourly_rate' => 100.00,
        ])
        ->call('save')
        ->assertHasNoFormErrors()
        ->assertNotified();

    $this->assertDatabaseHas(Timesheet::class, [
        'id' => $record->id,
        'total_cost' => 400.00,
    ]);
});

it('validates the form data', function (array $data, array $errors) {
    $employee = Employee::factory()->create();
    $project = Project::factory()->create();
    $record = Timesheet::factory()->create(['employee_id' => $employee->id, 'project_id' => $project->id]);

    Livewire::test(EditTimesheet::class, ['record' => $record->getRouteKey()])
        ->fillForm([
            'hours' => 6,
            'hourly_rate' => 100.00,
            ...$data,
        ])
        ->call('save')
        ->assertHasFormErrors($errors)
        ->assertNotNotified();
})->with([
    '`employee_id` is required' => [['employee_id' => null], ['employee_id' => 'required']],
    '`project_id` is required' => [['project_id' => null], ['project_id' => 'required']],
    '`date` is required' => [['date' => null], ['date' => 'required']],
    '`hours` is required' => [['hours' => null], ['hours' => 'required']],
    '`hours` must not exceed 999.9' => [['hours' => 1000], ['hours' => 'max']],
    '`hours` must not be negative' => [['hours' => -1], ['hours' => 'min']],
    '`minutes` is required' => [['minutes' => null], ['minutes' => 'required']],
    '`minutes` must not exceed 59' => [['minutes' => 60], ['minutes' => 'max']],
    '`minutes` must not be negative' => [['minutes' => -1], ['minutes' => 'min']],
    '`hourly_rate` is required' => [['hourly_rate' => null], ['hourly_rate' => 'required']],
    '`hourly_rate` must not exceed 999999.99' => [['hourly_rate' => 1000000], ['hourly_rate' => 'max']],
    '`hourly_rate` must not be negative' => [['hourly_rate' => -1], ['hourly_rate' => 'min']],
    '`description` is max 65535 characters' => [['description' => Str::random(65536)], ['description' => 'max']],
]);
