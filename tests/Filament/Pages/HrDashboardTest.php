<?php

use App\Filament\Pages\HrDashboard;
use App\Models\HR\Department;
use App\Models\HR\Employee;
use App\Models\HR\Expense;
use App\Models\HR\ExpenseLine;
use App\Models\HR\LeaveRequest;
use App\Models\HR\Project;
use App\Models\HR\Timesheet;

it('renders the hr dashboard page with all widgets', function () {
    $departments = Department::factory()->count(3)->create();

    $employees = Employee::factory()->count(10)->sequence(
        fn () => ['department_id' => $departments->random()->id],
    )->create();

    $projects = Project::factory()->count(3)->sequence(
        fn () => ['department_id' => $departments->random()->id],
    )->create();

    Timesheet::factory()->count(20)->sequence(
        fn () => [
            'employee_id' => $employees->random()->id,
            'project_id' => $projects->random()->id,
        ],
    )->create();

    LeaveRequest::factory()->count(15)->sequence(
        fn () => [
            'employee_id' => $employees->random()->id,
            'approver_id' => fake()->boolean(60) ? $employees->random()->id : null,
        ],
    )->create();

    $expenses = Expense::factory()->count(10)
        ->sequence(fn () => [
            'employee_id' => $employees->random()->id,
            'project_id' => fake()->boolean(60) ? $projects->random()->id : null,
        ])
        ->create();

    foreach ($expenses as $expense) {
        ExpenseLine::factory()->count(rand(1, 3))->create([
            'expense_id' => $expense->id,
        ]);
    }

    $this->get(HrDashboard::getUrl())
        ->assertOk();
});
