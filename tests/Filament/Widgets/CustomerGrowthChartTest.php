<?php

use App\Filament\Widgets\CustomerGrowthChart;
use App\Models\Shop\Customer;
use Livewire\Livewire;

it('renders the customer growth widget', function () {
    Customer::factory()->count(10)->create([
        'created_at' => fake()->dateTimeBetween('-11 months', 'now'),
    ]);

    Livewire::test(CustomerGrowthChart::class)
        ->assertOk();
});
