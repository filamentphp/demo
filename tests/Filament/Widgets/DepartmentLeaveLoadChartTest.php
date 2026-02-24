<?php

use App\Enums\LeaveStatus;
use App\Filament\Widgets\DepartmentLeaveLoadChart;
use App\Models\HR\Department;
use App\Models\HR\Employee;
use App\Models\HR\LeaveRequest;
use Livewire\Livewire;

it('renders the department leave load widget', function () {
    $departments = Department::factory()->count(3)->create();

    foreach ($departments as $department) {
        $employees = Employee::factory()->count(3)->create([
            'department_id' => $department->id,
        ]);

        foreach ($employees as $employee) {
            LeaveRequest::factory()->create([
                'employee_id' => $employee->id,
                'status' => fake()->randomElement([LeaveStatus::Approved, LeaveStatus::Taken]),
                'start_date' => fake()->dateTimeBetween(now()->startOfYear(), now()),
                'days_requested' => fake()->randomElement([1, 2, 3, 5]),
            ]);
        }
    }

    Livewire::test(DepartmentLeaveLoadChart::class)
        ->assertOk();
});
