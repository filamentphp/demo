<?php

use App\Enums\EmploymentType;
use App\Filament\Resources\HR\Employees\Pages\ListEmployees;
use App\Models\HR\Department;
use App\Models\HR\Employee;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\Testing\TestAction;
use Livewire\Livewire;

it('can render the list page', function () {
    $records = Employee::factory()->count(3)->create();

    Livewire::test(ListEmployees::class)
        ->assertOk()
        ->assertCanSeeTableRecords($records);
});

it('can view employee profile', function () {
    $record = Employee::factory()->create();

    Livewire::test(ListEmployees::class)
        ->callAction(TestAction::make('view_profile')->table($record))
        ->assertOk();
});

it('can toggle employee active status', function () {
    $record = Employee::factory()->create(['is_active' => true]);

    Livewire::test(ListEmployees::class)
        ->callAction(TestAction::make('toggle_active')->table($record));

    $this->assertDatabaseHas(Employee::class, ['id' => $record->id, 'is_active' => false]);
});

it('can change employee department', function () {
    $record = Employee::factory()->create();
    $newDepartment = Department::factory()->create();

    Livewire::test(ListEmployees::class)
        ->callAction(TestAction::make('change_department')->table($record), [
            'department_id' => $newDepartment->id,
        ]);

    $this->assertDatabaseHas(Employee::class, ['id' => $record->id, 'department_id' => $newDepartment->id]);
});

it('can delete an employee', function () {
    $record = Employee::factory()->create();

    Livewire::test(ListEmployees::class)
        ->callAction(TestAction::make(DeleteAction::class)->table($record))
        ->assertNotified();
});

it('can bulk change department', function () {
    $records = Employee::factory()->count(3)->create();
    $newDepartment = Department::factory()->create();

    Livewire::test(ListEmployees::class)
        ->selectTableRecords($records)
        ->callAction(TestAction::make('change_department')->table()->bulk(), [
            'department_id' => $newDepartment->id,
        ]);

    foreach ($records as $record) {
        $this->assertDatabaseHas(Employee::class, ['id' => $record->id, 'department_id' => $newDepartment->id]);
    }
});

it('can bulk toggle active', function () {
    $records = Employee::factory()->count(3)->create(['is_active' => true]);

    Livewire::test(ListEmployees::class)
        ->selectTableRecords($records)
        ->callAction(TestAction::make('toggle_active')->table()->bulk(), [
            'is_active' => '0',
        ]);

    foreach ($records as $record) {
        $this->assertDatabaseHas(Employee::class, ['id' => $record->id, 'is_active' => false]);
    }
});

it('can bulk delete employees', function () {
    $records = Employee::factory()->count(3)->create();

    Livewire::test(ListEmployees::class)
        ->selectTableRecords($records)
        ->callAction(TestAction::make(DeleteBulkAction::class)->table()->bulk())
        ->assertNotified();
});

it('can filter by employment type', function () {
    $fullTime = Employee::factory()->create(['employment_type' => EmploymentType::FullTime]);
    $partTime = Employee::factory()->create(['employment_type' => EmploymentType::PartTime]);

    Livewire::test(ListEmployees::class)
        ->filterTable('employment_type', EmploymentType::FullTime->value)
        ->assertCanSeeTableRecords([$fullTime])
        ->assertCanNotSeeTableRecords([$partTime]);
});

it('can filter by department', function () {
    $dept1 = Department::factory()->create();
    $dept2 = Department::factory()->create();
    $employee1 = Employee::factory()->create(['department_id' => $dept1->id]);
    $employee2 = Employee::factory()->create(['department_id' => $dept2->id]);

    Livewire::test(ListEmployees::class)
        ->filterTable('department', $dept1->id)
        ->assertCanSeeTableRecords([$employee1])
        ->assertCanNotSeeTableRecords([$employee2]);
});

it('can filter by active status', function () {
    $active = Employee::factory()->create(['is_active' => true]);
    $inactive = Employee::factory()->create(['is_active' => false]);

    Livewire::test(ListEmployees::class)
        ->filterTable('is_active', true)
        ->assertCanSeeTableRecords([$active])
        ->assertCanNotSeeTableRecords([$inactive]);
});

it('can filter trashed employees', function () {
    $activeEmployee = Employee::factory()->create();
    $trashedEmployee = Employee::factory()->create();
    $trashedEmployee->delete();

    Livewire::test(ListEmployees::class)
        ->assertCanSeeTableRecords([$activeEmployee])
        ->assertCanNotSeeTableRecords([$trashedEmployee])
        ->filterTable('trashed', true)
        ->assertCanSeeTableRecords([$trashedEmployee]);
});
