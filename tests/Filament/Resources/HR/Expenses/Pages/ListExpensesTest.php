<?php

use App\Enums\ExpenseStatus;
use App\Filament\Resources\HR\Expenses\Pages\ListExpenses;
use App\Models\HR\Employee;
use App\Models\HR\Expense;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\Testing\TestAction;
use Livewire\Livewire;

it('can render the list page', function () {
    $records = Expense::factory()->for(Employee::factory(), 'employee')->count(3)->create();

    Livewire::test(ListExpenses::class)
        ->assertOk()
        ->assertCanSeeTableRecords($records);
});

it('can approve a submitted expense', function () {
    $record = Expense::factory()->for(Employee::factory(), 'employee')->create([
        'status' => ExpenseStatus::Submitted,
        'total_amount' => 500,
    ]);

    Livewire::test(ListExpenses::class)
        ->callAction(TestAction::make('approve')->table($record))
        ->assertNotified();

    $record->refresh();
    expect($record->status)->toBe(ExpenseStatus::Approved);
    expect($record->approved_at)->not->toBeNull();
});

it('approve is hidden for non-submitted expenses', function () {
    $record = Expense::factory()->for(Employee::factory(), 'employee')->create([
        'status' => ExpenseStatus::Draft,
    ]);

    Livewire::test(ListExpenses::class)
        ->assertActionHidden(TestAction::make('approve')->table($record));
});

it('can reject a submitted expense', function () {
    $record = Expense::factory()->for(Employee::factory(), 'employee')->create([
        'status' => ExpenseStatus::Submitted,
        'total_amount' => 500,
    ]);

    Livewire::test(ListExpenses::class)
        ->callAction(TestAction::make('reject')->table($record), [
            'rejection_reason' => 'Missing receipts',
        ])
        ->assertNotified();

    $record->refresh();
    expect($record->status)->toBe(ExpenseStatus::Rejected);
    expect($record->notes)->toBe('Missing receipts');
});

it('reject is hidden for non-submitted expenses', function () {
    $record = Expense::factory()->for(Employee::factory(), 'employee')->create([
        'status' => ExpenseStatus::Draft,
    ]);

    Livewire::test(ListExpenses::class)
        ->assertActionHidden(TestAction::make('reject')->table($record));
});

it('can submit a draft expense', function () {
    $record = Expense::factory()->for(Employee::factory(), 'employee')->create([
        'status' => ExpenseStatus::Draft,
        'total_amount' => 500,
    ]);

    Livewire::test(ListExpenses::class)
        ->callAction(TestAction::make('submit')->table($record))
        ->assertNotified();

    $record->refresh();
    expect($record->status)->toBe(ExpenseStatus::Submitted);
    expect($record->submitted_at)->not->toBeNull();
});

it('submit is hidden for non-draft expenses', function () {
    $record = Expense::factory()->for(Employee::factory(), 'employee')->create([
        'status' => ExpenseStatus::Submitted,
    ]);

    Livewire::test(ListExpenses::class)
        ->assertActionHidden(TestAction::make('submit')->table($record));
});

it('cannot submit an expense with zero amount', function () {
    $record = Expense::factory()->for(Employee::factory(), 'employee')->create([
        'status' => ExpenseStatus::Draft,
        'total_amount' => 0,
    ]);

    Livewire::test(ListExpenses::class)
        ->callAction(TestAction::make('submit')->table($record));

    $record->refresh();
    expect($record->status)->toBe(ExpenseStatus::Draft);
});

it('can reimburse an approved expense', function () {
    $record = Expense::factory()->for(Employee::factory(), 'employee')->create([
        'status' => ExpenseStatus::Approved,
        'total_amount' => 500,
    ]);

    Livewire::test(ListExpenses::class)
        ->callAction(TestAction::make('reimburse')->table($record))
        ->assertNotified();

    $record->refresh();
    expect($record->status)->toBe(ExpenseStatus::Reimbursed);
});

it('reimburse is hidden for non-approved expenses', function () {
    $record = Expense::factory()->for(Employee::factory(), 'employee')->create([
        'status' => ExpenseStatus::Draft,
    ]);

    Livewire::test(ListExpenses::class)
        ->assertActionHidden(TestAction::make('reimburse')->table($record));
});

it('can flag an expense', function () {
    $record = Expense::factory()->for(Employee::factory(), 'employee')->create([
        'status' => ExpenseStatus::Submitted,
        'total_amount' => 500,
    ]);

    Livewire::test(ListExpenses::class)
        ->callAction(TestAction::make('flag')->table($record), [
            'flag_reason' => 'Suspicious amount',
        ])
        ->assertNotified();

    $record->refresh();
    expect($record->status)->toBe(ExpenseStatus::Draft);
    expect($record->notes)->toBe('Suspicious amount');
});

it('can delete an expense', function () {
    $record = Expense::factory()->for(Employee::factory(), 'employee')->create();

    Livewire::test(ListExpenses::class)
        ->callAction(TestAction::make(DeleteAction::class)->table($record))
        ->assertNotified();
});

it('can bulk delete expenses', function () {
    $records = Expense::factory()->for(Employee::factory(), 'employee')->count(3)->create();

    Livewire::test(ListExpenses::class)
        ->selectTableRecords($records)
        ->callAction(TestAction::make(DeleteBulkAction::class)->table()->bulk())
        ->assertNotified();
});
