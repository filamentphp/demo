<?php

namespace Database\Factories\HR;

use App\Models\HR\Timesheet;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Timesheet>
 */
class TimesheetFactory extends Factory
{
    /**
     * @var string
     */
    protected $model = Timesheet::class;

    public function definition(): array
    {
        $hours = $this->faker->randomElement([
            ...array_fill(0, 10, $this->faker->randomFloat(1, 6, 8)),
            ...array_fill(0, 5, $this->faker->randomFloat(1, 4, 6)),
            ...array_fill(0, 3, $this->faker->randomFloat(1, 1, 4)),
            ...array_fill(0, 2, $this->faker->randomFloat(1, 8, 10)),
        ]);
        $hours = round($hours * 2) / 2;
        $isBillable = $this->faker->boolean(80);
        $hourlyRate = $this->faker->randomFloat(2, 50, 200);

        return [
            'date' => $this->faker->dateTimeBetween('-90 days', 'now'),
            'hours' => $hours,
            'minutes' => $this->faker->randomElement([0, 0, 0, 15, 30, 45]),
            'description' => $this->faker->optional(0.6)->sentence(),
            'is_billable' => $isBillable,
            'hourly_rate' => $hourlyRate,
            'total_cost' => $hours * $hourlyRate,
        ];
    }
}
