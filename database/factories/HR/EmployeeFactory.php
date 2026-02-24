<?php

namespace Database\Factories\HR;

use App\Enums\EmploymentType;
use App\Models\HR\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * @var string
     */
    protected $model = Employee::class;

    /**
     * @var array<int, string>
     */
    protected static array $skills = [
        'PHP', 'Laravel', 'JavaScript', 'TypeScript', 'React',
        'Vue.js', 'Python', 'SQL', 'Docker', 'AWS',
    ];

    public function definition(): array
    {
        $type = $this->faker->randomElement([
            ...array_fill(0, 60, EmploymentType::FullTime),
            ...array_fill(0, 15, EmploymentType::PartTime),
            ...array_fill(0, 20, EmploymentType::Contractor),
            ...array_fill(0, 5, EmploymentType::Intern),
        ]);

        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'date_of_birth' => $this->faker->dateTimeBetween('-55 years', '-20 years'),
            'hire_date' => $this->faker->dateTimeBetween('-5 years', 'now'),
            'job_title' => $this->faker->jobTitle(),
            'employment_type' => $type,
            'salary' => match ($type) {
                EmploymentType::FullTime => $this->faker->randomFloat(2, 40000, 180000),
                EmploymentType::PartTime => $this->faker->randomFloat(2, 20000, 90000),
                default => null,
            },
            'hourly_rate' => match ($type) {
                EmploymentType::Contractor => $this->faker->randomFloat(2, 50, 200),
                EmploymentType::Intern => $this->faker->randomFloat(2, 15, 30),
                default => null,
            },
            'team_color' => $this->faker->hexColor(),
            'skills' => $this->faker->randomElements(static::$skills, $this->faker->numberBetween(2, 6)),
            'metadata' => [
                'certification' => $this->faker->randomElement(['AWS Certified', 'PMP', 'Scrum Master', 'None']),
                'office' => $this->faker->randomElement(['New York', 'San Francisco', 'London', 'Remote']),
            ],
            'is_active' => $this->faker->boolean(90),
        ];
    }
}
