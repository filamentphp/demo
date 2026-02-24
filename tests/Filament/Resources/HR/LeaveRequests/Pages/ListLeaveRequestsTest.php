<?php

use App\Enums\LeaveStatus;
use App\Filament\Resources\HR\LeaveRequests\Pages\ListLeaveRequests;
use App\Models\HR\Employee;
use App\Models\HR\LeaveRequest;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\Testing\TestAction;
use Livewire\Livewire;

it('can render the list page', function () {
    $records = LeaveRequest::factory()->for(Employee::factory(), 'employee')->count(3)->create();

    Livewire::test(ListLeaveRequests::class)
        ->assertOk()
        ->assertCanSeeTableRecords($records);
});

it('can approve a pending leave request', function () {
    $record = LeaveRequest::factory()->for(Employee::factory(), 'employee')->create([
        'status' => LeaveStatus::Pending,
    ]);

    Livewire::test(ListLeaveRequests::class)
        ->callAction(TestAction::make('approve')->table($record))
        ->assertNotified();

    $record->refresh();
    expect($record->status)->toBe(LeaveStatus::Approved);
    expect($record->reviewed_at)->not->toBeNull();
});

it('approve is hidden for non-pending requests', function () {
    $record = LeaveRequest::factory()->for(Employee::factory(), 'employee')->create([
        'status' => LeaveStatus::Approved,
    ]);

    Livewire::test(ListLeaveRequests::class)
        ->assertActionHidden(TestAction::make('approve')->table($record));
});

it('can reject a pending leave request', function () {
    $record = LeaveRequest::factory()->for(Employee::factory(), 'employee')->create([
        'status' => LeaveStatus::Pending,
    ]);

    Livewire::test(ListLeaveRequests::class)
        ->callAction(TestAction::make('reject')->table($record), [
            'reviewer_notes' => 'Team is understaffed',
        ])
        ->assertNotified();

    $record->refresh();
    expect($record->status)->toBe(LeaveStatus::Rejected);
    expect($record->reviewer_notes)->toBe('Team is understaffed');
    expect($record->reviewed_at)->not->toBeNull();
});

it('reject is hidden for non-pending requests', function () {
    $record = LeaveRequest::factory()->for(Employee::factory(), 'employee')->create([
        'status' => LeaveStatus::Approved,
    ]);

    Livewire::test(ListLeaveRequests::class)
        ->assertActionHidden(TestAction::make('reject')->table($record));
});

it('can delete a leave request', function () {
    $record = LeaveRequest::factory()->for(Employee::factory(), 'employee')->create();

    Livewire::test(ListLeaveRequests::class)
        ->callAction(TestAction::make(DeleteAction::class)->table($record))
        ->assertNotified();
});

it('can bulk approve leave requests', function () {
    $records = LeaveRequest::factory()->for(Employee::factory(), 'employee')->count(3)->create([
        'status' => LeaveStatus::Pending,
    ]);

    Livewire::test(ListLeaveRequests::class)
        ->selectTableRecords($records)
        ->callAction(TestAction::make('approve')->table()->bulk());

    foreach ($records as $record) {
        $record->refresh();
        expect($record->status)->toBe(LeaveStatus::Approved);
    }
});

it('can bulk reject leave requests', function () {
    $records = LeaveRequest::factory()->for(Employee::factory(), 'employee')->count(3)->create([
        'status' => LeaveStatus::Pending,
    ]);

    Livewire::test(ListLeaveRequests::class)
        ->selectTableRecords($records)
        ->callAction(TestAction::make('reject')->table()->bulk(), [
            'reviewer_notes' => 'Budget constraints',
        ]);

    foreach ($records as $record) {
        $record->refresh();
        expect($record->status)->toBe(LeaveStatus::Rejected);
    }
});

it('can bulk delete leave requests', function () {
    $records = LeaveRequest::factory()->for(Employee::factory(), 'employee')->count(3)->create();

    Livewire::test(ListLeaveRequests::class)
        ->selectTableRecords($records)
        ->callAction(TestAction::make(DeleteBulkAction::class)->table()->bulk())
        ->assertNotified();
});
