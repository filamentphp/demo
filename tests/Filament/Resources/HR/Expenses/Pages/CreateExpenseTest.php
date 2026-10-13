<?php

use App\Enums\ExpenseCategory;
use App\Enums\ExpenseStatus;
use App\Filament\Resources\HR\Expenses\Pages\CreateExpense;
use App\Models\HR\Employee;
use App\Models\HR\Expense;
use Filament\Actions\Testing\TestAction;
use Filament\Forms\Components\Repeater;
use Illuminate\Support\Str;
use Livewire\Livewire;

it('can render the create page', function () {
    Livewire::test(CreateExpense::class)
        ->assertOk();
});

it('can create a record', function () {
    $undoRepeaterFake = Repeater::fake();

    $employee = Employee::factory()->create();

    Livewire::test(CreateExpense::class)
        ->fillForm([
            'employee_id' => $employee->id,
            'category' => ExpenseCategory::Travel,
            'description' => 'Business trip expenses',
            'expenseLines' => [
                [
                    'description' => 'Flight ticket',
                    'quantity' => 1,
                    'unit_price' => 250.00,
                    'date' => now()->format('Y-m-d'),
                ],
            ],
            'currency' => 'USD',
        ])
        ->goToWizardStep(2)
        ->goToWizardStep(3)
        ->call('create')
        ->assertHasNoFormErrors()
        ->assertNotified();

    $this->assertDatabaseHas(Expense::class, ['employee_id' => $employee->id]);

    $undoRepeaterFake();
});

it('calculates total_amount from line items on create', function () {
    $undoRepeaterFake = Repeater::fake();

    $employee = Employee::factory()->create();

    Livewire::test(CreateExpense::class)
        ->fillForm([
            'employee_id' => $employee->id,
            'category' => ExpenseCategory::Travel,
            'description' => 'Business trip expenses',
            'expenseLines' => [
                [
                    'description' => 'Flight ticket',
                    'quantity' => 2,
                    'unit_price' => 50.00,
                    'date' => now()->format('Y-m-d'),
                ],
                [
                    'description' => 'Hotel stay',
                    'quantity' => 1,
                    'unit_price' => 100.00,
                    'date' => now()->format('Y-m-d'),
                ],
            ],
            'currency' => 'USD',
        ])
        ->goToWizardStep(2)
        ->goToWizardStep(3)
        ->assertSchemaStateSet(['total_amount' => '200.00'])
        ->fillForm(['total_amount' => 999.99])
        ->call('create')
        ->assertHasNoFormErrors()
        ->assertNotified();

    $expense = Expense::where('employee_id', $employee->id)->first();
    expect((float) $expense->total_amount)->toBe(200.00);

    $undoRepeaterFake();
});

it('updates the review total after editing adding and removing unsaved lines', function () {
    $undoRepeaterFake = Repeater::fake();

    Livewire::test(CreateExpense::class)
        ->fillForm([
            'employee_id' => Employee::factory()->create()->id,
            'category' => ExpenseCategory::Travel,
            'description' => 'Unsaved expenses',
        ])
        ->goToWizardStep(2)
        ->fillForm([
            'expenseLines.0.description' => 'Travel',
            'expenseLines.0.quantity' => 5,
            'expenseLines.0.unit_price' => 352.97,
        ])
        ->assertSchemaStateSet(['total_amount' => '1764.85'])
        ->fillForm(['expenseLines.0.quantity' => 2])
        ->assertSchemaStateSet(['total_amount' => '705.94'])
        ->fillForm(['expenseLines.0.unit_price' => 12.50])
        ->assertSchemaStateSet(['total_amount' => '25.00'])
        ->callAction(TestAction::make('add')->schemaComponent('expenseLines'))
        ->set('data.expenseLines.1.description', 'Meals')
        ->set('data.expenseLines.1.quantity', 3)
        ->set('data.expenseLines.1.unit_price', 20.25)
        ->assertSet('data.expenseLines.0.description', 'Travel')
        ->assertSet('data.expenseLines.1.description', 'Meals')
        ->assertSchemaStateSet(['total_amount' => '85.75'])
        ->callAction(TestAction::make('delete')->schemaComponent('expenseLines')->arguments(['item' => 0]))
        ->assertSchemaStateSet(['total_amount' => '60.75'])
        ->callAction(TestAction::make('delete')->schemaComponent('expenseLines')->arguments(['item' => 1]))
        ->assertSchemaStateSet(['total_amount' => '0.00'])
        ->callAction(TestAction::make('add')->schemaComponent('expenseLines'))
        ->fillForm([
            'expenseLines.0.description' => 'Taxi',
            'expenseLines.0.quantity' => 2,
            'expenseLines.0.unit_price' => 4.75,
        ])
        ->goToWizardStep(3)
        ->assertSchemaStateSet(['total_amount' => '9.50']);

    expect(Expense::count())->toBe(0);

    $undoRepeaterFake();
});

it('sets status to Draft on create', function () {
    $undoRepeaterFake = Repeater::fake();

    $employee = Employee::factory()->create();

    Livewire::test(CreateExpense::class)
        ->fillForm([
            'employee_id' => $employee->id,
            'category' => ExpenseCategory::Travel,
            'description' => 'Business trip',
            'expenseLines' => [
                [
                    'description' => 'Taxi',
                    'quantity' => 1,
                    'unit_price' => 25.00,
                    'date' => now()->format('Y-m-d'),
                ],
            ],
            'currency' => 'USD',
        ])
        ->goToWizardStep(2)
        ->goToWizardStep(3)
        ->call('create')
        ->assertHasNoFormErrors()
        ->assertNotified();

    $expense = Expense::where('employee_id', $employee->id)->first();
    expect($expense->status)->toBe(ExpenseStatus::Draft);

    $undoRepeaterFake();
});

it('validates expense line numeric fields', function (array $lineData, array $errors) {
    $undoRepeaterFake = Repeater::fake();

    $employee = Employee::factory()->create();

    Livewire::test(CreateExpense::class)
        ->fillForm([
            'employee_id' => $employee->id,
            'category' => ExpenseCategory::Travel,
            'description' => 'Business trip',
            'expenseLines' => [
                [
                    'description' => 'Item',
                    'quantity' => 1,
                    'unit_price' => 50.00,
                    'date' => now()->format('Y-m-d'),
                    ...$lineData,
                ],
            ],
            'currency' => 'USD',
        ])
        ->goToWizardStep(2)
        ->goToWizardStep(3)
        ->call('create')
        ->assertHasFormErrors($errors);

    $undoRepeaterFake();
})->with([
    '`quantity` must be at least 1' => [['quantity' => 0], ['expenseLines.0.quantity' => 'min']],
    '`quantity` must not be negative' => [['quantity' => -1], ['expenseLines.0.quantity' => 'min']],
    '`unit_price` must not be negative' => [['unit_price' => -1], ['expenseLines.0.unit_price' => 'min']],
    '`unit_price` must not exceed 99999999.99' => [['unit_price' => 100000000], ['expenseLines.0.unit_price' => 'max']],
]);

it('validates currency is required', function () {
    $undoRepeaterFake = Repeater::fake();

    $employee = Employee::factory()->create();

    Livewire::test(CreateExpense::class)
        ->fillForm([
            'employee_id' => $employee->id,
            'category' => ExpenseCategory::Travel,
            'description' => 'Business trip expenses',
            'expenseLines' => [
                [
                    'description' => 'Flight ticket',
                    'quantity' => 1,
                    'unit_price' => 250.00,
                    'date' => now()->format('Y-m-d'),
                ],
            ],
            'currency' => '',
        ])
        ->goToWizardStep(2)
        ->goToWizardStep(3)
        ->call('create')
        ->assertHasFormErrors(['currency' => 'required']);

    $undoRepeaterFake();
});

it('validates the form data', function (array $data, array $errors) {
    $employee = Employee::factory()->create();

    Livewire::test(CreateExpense::class)
        ->fillForm([
            'employee_id' => $employee->id,
            'category' => ExpenseCategory::Travel,
            'description' => 'Business trip expenses',
            ...$data,
        ])
        ->goToNextWizardStep()
        ->assertHasFormErrors($errors);
})->with([
    '`employee_id` is required' => [['employee_id' => null], ['employee_id' => 'required']],
    '`category` is required' => [['category' => null], ['category' => 'required']],
    '`description` is required' => [['description' => null], ['description' => 'required']],
    '`description` is max 65535 characters' => [['description' => Str::random(65536)], ['description' => 'max']],
]);
