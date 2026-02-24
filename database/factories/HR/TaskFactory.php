<?php

namespace Database\Factories\HR;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\HR\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    /**
     * @var string
     */
    protected $model = Task::class;

    /**
     * @var array<int, string>
     */
    protected static array $labels = [
        'bug', 'feature', 'enhancement', 'documentation',
        'design', 'testing', 'refactor', 'urgent',
    ];

    public function definition(): array
    {
        $status = $this->faker->randomElement(TaskStatus::cases());
        $estimatedHours = $this->faker->randomFloat(1, 1, 40);

        return [
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->optional(0.7)->paragraph(),
            'status' => $status,
            'priority' => $this->faker->randomElement(TaskPriority::cases()),
            'estimated_hours' => $estimatedHours,
            'actual_hours' => $status === TaskStatus::Completed
                ? $this->faker->randomFloat(1, $estimatedHours * 0.5, $estimatedHours * 1.5)
                : $this->faker->randomFloat(1, 0, $estimatedHours * 0.8),
            'due_date' => $this->faker->optional(0.8)->dateTimeBetween('-1 month', '+3 months'),
            'completed_at' => $status === TaskStatus::Completed
                ? $this->faker->dateTimeBetween('-2 months', 'now')
                : null,
            'labels' => $this->faker->randomElements(static::$labels, $this->faker->numberBetween(1, 3)),
            'sort' => $this->faker->numberBetween(0, 100),
        ];
    }
}
