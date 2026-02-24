<?php

use App\Enums\LeaveStatus;
use App\Filament\Resources\HR\LeaveRequests\Pages\ViewLeaveRequest;
use App\Models\HR\Employee;
use App\Models\HR\LeaveRequest;
use Livewire\Livewire;

it('can render the view page', function () {
    $employee = Employee::factory()->create();
    $record = LeaveRequest::factory()->create(['employee_id' => $employee->id]);

    Livewire::test(ViewLeaveRequest::class, ['record' => $record->getRouteKey()])
        ->assertOk();
});

it('can approve a pending leave request', function () {
    $employee = Employee::factory()->create();
    $record = LeaveRequest::factory()->create([
        'employee_id' => $employee->id,
        'status' => LeaveStatus::Pending,
    ]);

    Livewire::test(ViewLeaveRequest::class, ['record' => $record->getRouteKey()])
        ->callAction('approve')
        ->assertNotified();

    $record->refresh();
    expect($record->status)->toBe(LeaveStatus::Approved);
    expect($record->reviewed_at)->not->toBeNull();
});

it('approve is hidden for non-pending requests', function () {
    $employee = Employee::factory()->create();
    $record = LeaveRequest::factory()->create([
        'employee_id' => $employee->id,
        'status' => LeaveStatus::Approved,
    ]);

    Livewire::test(ViewLeaveRequest::class, ['record' => $record->getRouteKey()])
        ->assertActionHidden('approve');
});

it('can reject a pending leave request', function () {
    $employee = Employee::factory()->create();
    $record = LeaveRequest::factory()->create([
        'employee_id' => $employee->id,
        'status' => LeaveStatus::Pending,
    ]);

    Livewire::test(ViewLeaveRequest::class, ['record' => $record->getRouteKey()])
        ->callAction('reject', [
            'reviewer_notes' => 'Team is understaffed',
        ])
        ->assertNotified();

    $record->refresh();
    expect($record->status)->toBe(LeaveStatus::Rejected);
    expect($record->reviewer_notes)->toBe('Team is understaffed');
});

it('reject is hidden for non-pending requests', function () {
    $employee = Employee::factory()->create();
    $record = LeaveRequest::factory()->create([
        'employee_id' => $employee->id,
        'status' => LeaveStatus::Approved,
    ]);

    Livewire::test(ViewLeaveRequest::class, ['record' => $record->getRouteKey()])
        ->assertActionHidden('reject');
});
