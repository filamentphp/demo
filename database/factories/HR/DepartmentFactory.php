<?php

namespace Database\Factories\HR;

use App\Models\HR\Department;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Department>
 */
class DepartmentFactory extends Factory
{
    /**
     * @var string
     */
    protected $model = Department::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->randomElement([
            'Engineering', 'Marketing', 'Sales', 'Human Resources',
            'Finance', 'Operations', 'Product', 'Design',
            'Customer Support', 'Legal',
        ]);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => $this->faker->sentence(),
            'budget' => $this->faker->randomFloat(2, 50000, 500000),
            'headcount_limit' => $this->faker->numberBetween(5, 30),
            'color' => $this->faker->hexColor(),
            'is_active' => true,
        ];
    }

    public function child(Department $parent): static
    {
        return $this->state(fn () => [
            'parent_id' => $parent->id,
            'name' => $this->faker->unique()->randomElement([
                'Frontend', 'Backend', 'DevOps', 'QA',
                'Content', 'SEO', 'Paid Media',
                'Inside Sales', 'Field Sales',
                'Payroll', 'Benefits', 'Recruiting',
            ]),
        ])->afterMaking(function (Department $department): void {
            $department->slug = Str::slug($department->name);
        });
    }
}
