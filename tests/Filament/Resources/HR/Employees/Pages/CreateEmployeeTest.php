<?php

use App\Filament\Resources\HR\Employees\Pages\CreateEmployee;
use App\Models\HR\Employee;
use Illuminate\Support\Str;
use Livewire\Livewire;

it('can render the create page', function () {
    Livewire::test(CreateEmployee::class)
        ->assertOk();
});

it('can create a record', function () {
    $data = Employee::factory()->make();

    Livewire::test(CreateEmployee::class)
        ->fillForm([
            'name' => $data->name,
            'email' => $data->email,
            'job_title' => $data->job_title,
            'employment_type' => $data->employment_type,
            'hire_date' => $data->hire_date,
            'salary' => $data->salary,
        ])
        ->call('create')
        ->assertNotified()
        ->assertRedirect();

    $this->assertDatabaseHas(Employee::class, ['email' => $data->email]);
});

it('validates the form data', function (array $data, array $errors) {
    $newData = Employee::factory()->make();

    Livewire::test(CreateEmployee::class)
        ->fillForm([
            'name' => $newData->name,
            'email' => $newData->email,
            'job_title' => $newData->job_title,
            'employment_type' => $newData->employment_type,
            'hire_date' => $newData->hire_date,
            'salary' => $newData->salary,
            ...$data,
        ])
        ->call('create')
        ->assertHasFormErrors($errors)
        ->assertNotNotified()
        ->assertNoRedirect();
})->with([
    '`name` is required' => [['name' => null], ['name' => 'required']],
    '`name` is max 255 characters' => [['name' => Str::random(256)], ['name' => 'max']],
    '`email` is required' => [['email' => null], ['email' => 'required']],
    '`email` must be valid' => [['email' => Str::random()], ['email' => 'email']],
    '`email` is max 255 characters' => [['email' => Str::random(256) . '@example.com'], ['email' => 'max']],
    '`phone` is max 255 characters' => [['phone' => Str::random(256)], ['phone' => 'max']],
    '`job_title` is required' => [['job_title' => null], ['job_title' => 'required']],
    '`job_title` is max 255 characters' => [['job_title' => Str::random(256)], ['job_title' => 'max']],
    '`employment_type` is required' => [['employment_type' => null], ['employment_type' => 'required']],
    '`hire_date` is required' => [['hire_date' => null], ['hire_date' => 'required']],
    '`salary` must not exceed 99999999.99' => [['employment_type' => 'full_time', 'salary' => 100000000], ['salary' => 'max']],
    '`salary` must not be negative' => [['employment_type' => 'full_time', 'salary' => -1], ['salary' => 'min']],
    '`hourly_rate` must not exceed 999999.99' => [['employment_type' => 'contractor', 'hourly_rate' => 1000000], ['hourly_rate' => 'max']],
    '`hourly_rate` must not be negative' => [['employment_type' => 'contractor', 'hourly_rate' => -1], ['hourly_rate' => 'min']],
]);
