<?php

use App\Enums\LeaveType;
use App\Filament\Resources\HR\LeaveRequests\Pages\CreateLeaveRequest;
use App\Models\HR\Employee;
use App\Models\HR\LeaveRequest;
use Illuminate\Support\Str;
use Livewire\Livewire;

it('can render the create page', function () {
    Livewire::test(CreateLeaveRequest::class)
        ->assertOk();
});

it('can create a record', function () {
    $employee = Employee::factory()->create();

    Livewire::test(CreateLeaveRequest::class)
        ->fillForm([
            'employee_id' => $employee->id,
            'type' => LeaveType::Annual,
            'start_date' => '2026-03-01',
            'end_date' => '2026-03-05',
            'reason' => 'Family vacation',
        ])
        ->call('create')
        ->assertNotified()
        ->assertRedirect();

    $this->assertDatabaseHas(LeaveRequest::class, ['employee_id' => $employee->id]);
});

it('calculates days_requested on create', function () {
    $employee = Employee::factory()->create();

    Livewire::test(CreateLeaveRequest::class)
        ->fillForm([
            'employee_id' => $employee->id,
            'type' => LeaveType::Annual,
            'start_date' => '2026-03-01',
            'end_date' => '2026-03-05',
            'reason' => 'Family vacation',
        ])
        ->call('create')
        ->assertHasNoFormErrors()
        ->assertNotified();

    $this->assertDatabaseHas(LeaveRequest::class, [
        'employee_id' => $employee->id,
        'days_requested' => 5,
    ]);
});

it('calculates days_requested for same day', function () {
    $employee = Employee::factory()->create();

    Livewire::test(CreateLeaveRequest::class)
        ->fillForm([
            'employee_id' => $employee->id,
            'type' => LeaveType::Annual,
            'start_date' => '2026-03-01',
            'end_date' => '2026-03-01',
            'reason' => 'Doctor appointment',
        ])
        ->call('create')
        ->assertHasNoFormErrors()
        ->assertNotified();

    $this->assertDatabaseHas(LeaveRequest::class, [
        'employee_id' => $employee->id,
        'days_requested' => 1,
    ]);
});

it('validates the form data', function (array $data, array $errors) {
    $employee = Employee::factory()->create();

    Livewire::test(CreateLeaveRequest::class)
        ->fillForm([
            'employee_id' => $employee->id,
            'type' => LeaveType::Annual,
            'start_date' => '2026-03-01',
            'end_date' => '2026-03-05',
            'reason' => 'Family vacation',
            ...$data,
        ])
        ->call('create')
        ->assertHasFormErrors($errors)
        ->assertNotNotified()
        ->assertNoRedirect();
})->with([
    '`employee_id` is required' => [['employee_id' => null], ['employee_id' => 'required']],
    '`type` is required' => [['type' => null], ['type' => 'required']],
    '`start_date` is required' => [['start_date' => null], ['start_date' => 'required']],
    '`end_date` is required' => [['end_date' => null], ['end_date' => 'required']],
    '`reason` is required' => [['reason' => null], ['reason' => 'required']],
    '`reason` is max 65535 characters' => [['reason' => Str::random(65536)], ['reason' => 'max']],
]);
