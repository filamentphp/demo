<?php

use App\Enums\ExpenseStatus;
use App\Enums\ProjectStatus;
use App\Filament\Widgets\BudgetBurnRateChart;
use App\Models\HR\Department;
use App\Models\HR\Employee;
use App\Models\HR\Expense;
use App\Models\HR\Project;
use Livewire\Livewire;

it('renders the budget burn rate widget', function () {
    $department = Department::factory()->create();
    $employee = Employee::factory()->create(['department_id' => $department->id]);

    $project = Project::factory()->create([
        'department_id' => $department->id,
        'status' => ProjectStatus::Active,
        'budget' => 100000,
    ]);

    // Approved expenses spread across recent months
    Expense::factory()->count(5)->create([
        'employee_id' => $employee->id,
        'project_id' => $project->id,
        'status' => ExpenseStatus::Approved,
        'total_amount' => fake()->randomFloat(2, 500, 5000),
        'created_at' => fake()->dateTimeBetween('-6 months', 'now'),
    ]);

    // Reimbursed expenses
    Expense::factory()->count(3)->create([
        'employee_id' => $employee->id,
        'project_id' => $project->id,
        'status' => ExpenseStatus::Reimbursed,
        'total_amount' => fake()->randomFloat(2, 200, 2000),
        'created_at' => fake()->dateTimeBetween('-10 months', '-3 months'),
    ]);

    // Draft expense (should be excluded from cumulative)
    Expense::factory()->create([
        'employee_id' => $employee->id,
        'project_id' => $project->id,
        'status' => ExpenseStatus::Draft,
        'total_amount' => 9999.99,
        'created_at' => now()->subMonth(),
    ]);

    Livewire::test(BudgetBurnRateChart::class)
        ->assertOk();
});
