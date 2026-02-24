<?php

use App\Enums\ProjectStatus;
use App\Filament\Widgets\ProjectHealthChart;
use App\Models\HR\Department;
use App\Models\HR\Project;
use Livewire\Livewire;

it('renders the project health widget', function () {
    $department = Department::factory()->create();

    Project::factory()->count(5)->create([
        'department_id' => $department->id,
        'status' => ProjectStatus::Active,
        'budget' => fake()->randomFloat(2, 50000, 200000),
        'spent' => fake()->randomFloat(2, 10000, 150000),
        'start_date' => fake()->dateTimeBetween('-3 months', '-1 month'),
        'end_date' => fake()->dateTimeBetween('+1 month', '+6 months'),
    ]);

    Livewire::test(ProjectHealthChart::class)
        ->assertOk();
});
