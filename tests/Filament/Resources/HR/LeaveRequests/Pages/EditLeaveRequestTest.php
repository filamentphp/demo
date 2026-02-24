<?php

use App\Filament\Resources\HR\LeaveRequests\Pages\EditLeaveRequest;
use App\Models\HR\Employee;
use App\Models\HR\LeaveRequest;
use Illuminate\Support\Str;
use Livewire\Livewire;

it('can render the edit page', function () {
    $employee = Employee::factory()->create();
    $record = LeaveRequest::factory()->create(['employee_id' => $employee->id]);

    Livewire::test(EditLeaveRequest::class, ['record' => $record->getRouteKey()])
        ->assertOk();
});

it('can update a record', function () {
    $employee = Employee::factory()->create();
    $record = LeaveRequest::factory()->create(['employee_id' => $employee->id]);

    Livewire::test(EditLeaveRequest::class, ['record' => $record->getRouteKey()])
        ->fillForm([
            'reason' => 'Updated reason for leave',
        ])
        ->call('save')
        ->assertNotified();

    $this->assertDatabaseHas(LeaveRequest::class, [
        'id' => $record->id,
        'reason' => 'Updated reason for leave',
    ]);
});

it('recalculates days_requested on save', function () {
    $employee = Employee::factory()->create();
    $record = LeaveRequest::factory()->create([
        'employee_id' => $employee->id,
        'start_date' => '2026-03-01',
        'end_date' => '2026-03-05',
        'days_requested' => 5,
    ]);

    Livewire::test(EditLeaveRequest::class, ['record' => $record->getRouteKey()])
        ->fillForm([
            'start_date' => '2026-04-01',
            'end_date' => '2026-04-10',
        ])
        ->call('save')
        ->assertHasNoFormErrors()
        ->assertNotified();

    $this->assertDatabaseHas(LeaveRequest::class, [
        'id' => $record->id,
        'days_requested' => 10,
    ]);
});

it('validates the form data', function (array $data, array $errors) {
    $employee = Employee::factory()->create();
    $record = LeaveRequest::factory()->create(['employee_id' => $employee->id]);

    Livewire::test(EditLeaveRequest::class, ['record' => $record->getRouteKey()])
        ->fillForm([
            'reason' => 'Valid reason',
            ...$data,
        ])
        ->call('save')
        ->assertHasFormErrors($errors)
        ->assertNotNotified();
})->with([
    '`employee_id` is required' => [['employee_id' => null], ['employee_id' => 'required']],
    '`type` is required' => [['type' => null], ['type' => 'required']],
    '`start_date` is required' => [['start_date' => null], ['start_date' => 'required']],
    '`end_date` is required' => [['end_date' => null], ['end_date' => 'required']],
    '`reason` is required' => [['reason' => null], ['reason' => 'required']],
    '`reason` is max 65535 characters' => [['reason' => Str::random(65536)], ['reason' => 'max']],
]);
