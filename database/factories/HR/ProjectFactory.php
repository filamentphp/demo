<?php

namespace Database\Factories\HR;

use App\Enums\ProjectStatus;
use App\Enums\TaskPriority;
use App\Models\HR\Project;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    /**
     * @var string
     */
    protected $model = Project::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->catchPhrase();
        $budget = $this->faker->randomFloat(2, 10000, 500000);
        $estimatedHours = $this->faker->randomFloat(1, 100, 2000);
        $status = $this->faker->randomElement(ProjectStatus::cases());
        $startDate = $this->faker->dateTimeBetween('-6 months', '-1 month');

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => $this->faker->paragraphs(2, true),
            'color' => $this->faker->hexColor(),
            'status' => $status,
            'priority' => $this->faker->randomElement(TaskPriority::cases()),
            'budget' => $budget,
            'spent' => $this->faker->randomFloat(2, 0, $budget * 1.2),
            'estimated_hours' => $estimatedHours,
            'actual_hours' => $this->faker->randomFloat(1, 0, $estimatedHours * 1.3),
            'start_date' => $startDate,
            'end_date' => $this->faker->optional(0.7)->dateTimeBetween('+1 month', '+12 months'),
            'plan' => $this->generatePlan(),
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    protected function generatePlan(): array
    {
        $blocks = [];
        $count = $this->faker->numberBetween(2, 5);

        for ($i = 0; $i < $count; $i++) {
            $type = $this->faker->randomElement(['milestone', 'task_group', 'checkpoint']);

            $blocks[] = match ($type) {
                'milestone' => [
                    'type' => 'milestone',
                    'data' => [
                        'title' => $this->faker->sentence(3),
                        'target_date' => $this->faker->dateTimeBetween('+1 month', '+6 months')->format('Y-m-d'),
                        'description' => $this->faker->sentence(),
                    ],
                ],
                'task_group' => [
                    'type' => 'task_group',
                    'data' => [
                        'title' => $this->faker->sentence(3),
                        'assignee' => null,
                        'tasks' => $this->faker->words($this->faker->numberBetween(2, 5)),
                    ],
                ],
                'checkpoint' => [
                    'type' => 'checkpoint',
                    'data' => [
                        'title' => $this->faker->sentence(3),
                        'date' => $this->faker->dateTimeBetween('+1 month', '+6 months')->format('Y-m-d'),
                        'status' => $this->faker->randomElement(['pending', 'passed', 'failed']),
                    ],
                ],
            };
        }

        return $blocks;
    }
}
