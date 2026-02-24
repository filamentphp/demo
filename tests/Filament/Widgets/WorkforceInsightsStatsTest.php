<?php

use App\Enums\EmploymentType;
use App\Filament\Widgets\WorkforceInsightsStats;
use App\Models\HR\Department;
use App\Models\HR\Employee;
use Livewire\Livewire;

it('renders the workforce insights widget', function () {
    $department = Department::factory()->create([
        'headcount_limit' => 20,
        'is_active' => true,
    ]);

    // Active full-time employees with hire dates
    Employee::factory()->count(5)->create([
        'department_id' => $department->id,
        'employment_type' => EmploymentType::FullTime,
        'is_active' => true,
        'hire_date' => fake()->dateTimeBetween('-3 years', '-6 months'),
    ]);

    // Active contractors
    Employee::factory()->count(2)->create([
        'department_id' => $department->id,
        'employment_type' => EmploymentType::Contractor,
        'is_active' => true,
        'hire_date' => fake()->dateTimeBetween('-1 year', '-1 month'),
    ]);

    // Inactive (departed) employee
    Employee::factory()->create([
        'department_id' => $department->id,
        'is_active' => false,
        'deleted_at' => now()->subMonth(),
    ]);

    Livewire::test(WorkforceInsightsStats::class)
        ->assertOk();
});
