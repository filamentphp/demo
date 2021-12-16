<?php

declare(strict_types=1);

namespace Database\Factories\Shop;

use App\Models\Shop\Discount;
use Illuminate\Database\Eloquent\Factories\Factory;

final class DiscountFactory extends Factory
{
    /**
     * @var string
     */
    protected $model = Discount::class;

    public function definition(): array
    {
        return [
            'code' => $this->faker->unique()->regexify('[A-Z0-9]{6}'),
            'type' => $this->faker->randomElement(['percentage', 'fixed_amount']),
            'value' => $this->faker->randomFloat(2, 2, 10),
            'usage_limit' => $this->faker->numberBetween(10, 100),
            'usage_limit_per_customer' => $this->faker->numberBetween(1, 5),
            'starts_at' => $this->faker->dateTimeBetween('-6 month', '+1 month'),
            'ends_at' => $this->faker->dateTimeBetween('+1 month', '+2 months'),
            'is_visible' => $this->faker->boolean(),
            'created_at' => $this->faker->dateTimeBetween('-1 year', '-6 month'),
            'updated_at' => $this->faker->dateTimeBetween('-5 month', 'now'),
        ];
    }
}
