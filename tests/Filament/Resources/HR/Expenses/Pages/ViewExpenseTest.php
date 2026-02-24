<?php

use App\Enums\ExpenseStatus;
use App\Filament\Resources\HR\Expenses\Pages\ViewExpense;
use App\Models\HR\Employee;
use App\Models\HR\Expense;
use Livewire\Livewire;

it('can render the view page', function () {
    $record = Expense::factory()->for(Employee::factory(), 'employee')->create();

    Livewire::test(ViewExpense::class, ['record' => $record->getRouteKey()])
        ->assertOk();
});

it('can submit a draft expense', function () {
    $record = Expense::factory()->for(Employee::factory(), 'employee')->create([
        'status' => ExpenseStatus::Draft,
        'total_amount' => 500,
    ]);

    Livewire::test(ViewExpense::class, ['record' => $record->getRouteKey()])
        ->callAction('submit')
        ->assertNotified();

    $record->refresh();
    expect($record->status)->toBe(ExpenseStatus::Submitted);
});

it('submit is hidden for non-draft expenses', function () {
    $record = Expense::factory()->for(Employee::factory(), 'employee')->create([
        'status' => ExpenseStatus::Submitted,
    ]);

    Livewire::test(ViewExpense::class, ['record' => $record->getRouteKey()])
        ->assertActionHidden('submit');
});

it('cannot submit an expense with zero amount', function () {
    $record = Expense::factory()->for(Employee::factory(), 'employee')->create([
        'status' => ExpenseStatus::Draft,
        'total_amount' => 0,
    ]);

    Livewire::test(ViewExpense::class, ['record' => $record->getRouteKey()])
        ->callAction('submit');

    $record->refresh();
    expect($record->status)->toBe(ExpenseStatus::Draft);
});

it('can approve a submitted expense', function () {
    $record = Expense::factory()->for(Employee::factory(), 'employee')->create([
        'status' => ExpenseStatus::Submitted,
        'total_amount' => 500,
    ]);

    Livewire::test(ViewExpense::class, ['record' => $record->getRouteKey()])
        ->callAction('approve')
        ->assertNotified();

    $record->refresh();
    expect($record->status)->toBe(ExpenseStatus::Approved);
});

it('approve is hidden for non-submitted expenses', function () {
    $record = Expense::factory()->for(Employee::factory(), 'employee')->create([
        'status' => ExpenseStatus::Draft,
    ]);

    Livewire::test(ViewExpense::class, ['record' => $record->getRouteKey()])
        ->assertActionHidden('approve');
});

it('can reject a submitted expense', function () {
    $record = Expense::factory()->for(Employee::factory(), 'employee')->create([
        'status' => ExpenseStatus::Submitted,
        'total_amount' => 500,
    ]);

    Livewire::test(ViewExpense::class, ['record' => $record->getRouteKey()])
        ->callAction('reject', [
            'rejection_reason' => 'Missing receipts',
        ])
        ->assertNotified();

    $record->refresh();
    expect($record->status)->toBe(ExpenseStatus::Rejected);
});

it('reject is hidden for non-submitted expenses', function () {
    $record = Expense::factory()->for(Employee::factory(), 'employee')->create([
        'status' => ExpenseStatus::Draft,
    ]);

    Livewire::test(ViewExpense::class, ['record' => $record->getRouteKey()])
        ->assertActionHidden('reject');
});

it('can reimburse an approved expense', function () {
    $record = Expense::factory()->for(Employee::factory(), 'employee')->create([
        'status' => ExpenseStatus::Approved,
        'total_amount' => 500,
    ]);

    Livewire::test(ViewExpense::class, ['record' => $record->getRouteKey()])
        ->callAction('reimburse')
        ->assertNotified();

    $record->refresh();
    expect($record->status)->toBe(ExpenseStatus::Reimbursed);
});

it('reimburse is hidden for non-approved expenses', function () {
    $record = Expense::factory()->for(Employee::factory(), 'employee')->create([
        'status' => ExpenseStatus::Draft,
    ]);

    Livewire::test(ViewExpense::class, ['record' => $record->getRouteKey()])
        ->assertActionHidden('reimburse');
});

it('can flag an expense', function () {
    $record = Expense::factory()->for(Employee::factory(), 'employee')->create([
        'status' => ExpenseStatus::Submitted,
        'total_amount' => 500,
    ]);

    Livewire::test(ViewExpense::class, ['record' => $record->getRouteKey()])
        ->callAction('flag', [
            'flag_reason' => 'Suspicious amount',
        ])
        ->assertNotified();

    $record->refresh();
    expect($record->status)->toBe(ExpenseStatus::Draft);
});
