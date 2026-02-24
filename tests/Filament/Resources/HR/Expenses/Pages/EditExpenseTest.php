<?php

use App\Enums\ExpenseCategory;
use App\Filament\Resources\HR\Expenses\Pages\EditExpense;
use App\Models\HR\Employee;
use App\Models\HR\Expense;
use App\Models\HR\ExpenseLine;
use Filament\Forms\Components\Repeater;
use Illuminate\Support\Str;
use Livewire\Livewire;

it('can render the edit page', function () {
    $record = Expense::factory()->for(Employee::factory(), 'employee')->has(ExpenseLine::factory()->count(1), 'expenseLines')->create();

    Livewire::test(EditExpense::class, ['record' => $record->getRouteKey()])
        ->assertOk();
});

it('can update a record', function () {
    $record = Expense::factory()->for(Employee::factory(), 'employee')->has(ExpenseLine::factory()->count(1), 'expenseLines')->create();

    Livewire::test(EditExpense::class, ['record' => $record->getRouteKey()])
        ->fillForm([
            'category' => ExpenseCategory::Meals,
            'description' => 'Updated description',
        ])
        ->call('save')
        ->assertNotified();

    $this->assertDatabaseHas(Expense::class, [
        'id' => $record->id,
        'description' => 'Updated description',
    ]);
});

it('recalculates total_amount on save', function () {
    $record = Expense::factory()->for(Employee::factory(), 'employee')->create([
        'total_amount' => 100.00,
    ]);
    $record->expenseLines()->createMany([
        ['description' => 'Item 1', 'quantity' => 1, 'unit_price' => 50.00, 'amount' => 50.00, 'date' => now()],
        ['description' => 'Item 2', 'quantity' => 1, 'unit_price' => 50.00, 'amount' => 50.00, 'date' => now()],
    ]);

    Livewire::test(EditExpense::class, ['record' => $record->getRouteKey()])
        ->fillForm([
            'description' => 'Updated description',
        ])
        ->call('save')
        ->assertHasNoFormErrors()
        ->assertNotified();

    $record->refresh();
    expect((float) $record->total_amount)->toBe(100.00);
});

it('validates the form data', function (array $data, array $errors) {
    $record = Expense::factory()->for(Employee::factory(), 'employee')->has(ExpenseLine::factory()->count(1), 'expenseLines')->create();

    Livewire::test(EditExpense::class, ['record' => $record->getRouteKey()])
        ->fillForm([
            'category' => ExpenseCategory::Travel,
            'description' => 'Valid description',
            ...$data,
        ])
        ->call('save')
        ->assertHasFormErrors($errors)
        ->assertNotNotified();
})->with([
    '`employee_id` is required' => [['employee_id' => null], ['employee_id' => 'required']],
    '`category` is required' => [['category' => null], ['category' => 'required']],
    '`description` is required' => [['description' => null], ['description' => 'required']],
    '`description` is max 65535 characters' => [['description' => Str::random(65536)], ['description' => 'max']],
    '`currency` is required' => [['currency' => null], ['currency' => 'required']],
]);

it('validates expense line numeric fields', function (array $lineData, array $errors) {
    $undoRepeaterFake = Repeater::fake();

    $employee = Employee::factory()->create();
    $record = Expense::factory()->for($employee, 'employee')->create();
    $record->expenseLines()->create([
        'description' => 'Item',
        'quantity' => 1,
        'unit_price' => 50.00,
        'amount' => 50.00,
        'date' => now(),
    ]);

    Livewire::test(EditExpense::class, ['record' => $record->getRouteKey()])
        ->fillForm([
            'expenseLines' => [
                [
                    'description' => 'Item',
                    'quantity' => 1,
                    'unit_price' => 50.00,
                    'date' => now()->format('Y-m-d'),
                    ...$lineData,
                ],
            ],
        ])
        ->call('save')
        ->assertHasFormErrors($errors);

    $undoRepeaterFake();
})->with([
    '`quantity` must be at least 1' => [['quantity' => 0], ['expenseLines.0.quantity' => 'min']],
    '`quantity` must not be negative' => [['quantity' => -1], ['expenseLines.0.quantity' => 'min']],
    '`unit_price` must not be negative' => [['unit_price' => -1], ['expenseLines.0.unit_price' => 'min']],
    '`unit_price` must not exceed 99999999.99' => [['unit_price' => 100000000], ['expenseLines.0.unit_price' => 'max']],
]);
