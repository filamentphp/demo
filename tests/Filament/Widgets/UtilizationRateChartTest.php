<?php

use App\Enums\EmploymentType;
use App\Filament\Widgets\UtilizationRateChart;
use App\Models\HR\Department;
use App\Models\HR\Employee;
use App\Models\HR\Project;
use App\Models\HR\Timesheet;
use Livewire\Livewire;

it('renders the utilization rate widget', function () {
    $department = Department::factory()->create();
    $project = Project::factory()->create(['department_id' => $department->id]);

    $employees = Employee::factory()->count(3)->create([
        'department_id' => $department->id,
        'employment_type' => EmploymentType::FullTime,
        'is_active' => true,
    ]);

    // Billable timesheets across recent weeks
    foreach ($employees as $employee) {
        Timesheet::factory()->count(5)->create([
            'employee_id' => $employee->id,
            'project_id' => $project->id,
            'is_billable' => true,
            'hours' => fake()->randomFloat(1, 4, 8),
            'date' => fake()->dateTimeBetween('-8 weeks', 'now'),
        ]);
    }

    // Non-billable timesheet
    Timesheet::factory()->create([
        'employee_id' => $employees->first()->id,
        'project_id' => $project->id,
        'is_billable' => false,
        'hours' => 3.0,
        'date' => now()->subWeek(),
    ]);

    Livewire::test(UtilizationRateChart::class)
        ->assertOk();
});
