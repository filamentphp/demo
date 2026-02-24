<?php

namespace Database\Factories\Shop;

use App\Enums\CurrencyCode;
use App\Models\Shop\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    /**
     * @var string
     */
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'number' => 'OR' . $this->faker->unique()->randomNumber(6),
            'currency' => $this->faker->randomElement(CurrencyCode::cases())->value,
            'total_price' => $this->faker->randomFloat(2, 100, 2000),
            'status' => $this->faker->randomElement([
                // Realistic distribution: ~40% delivered, ~20% shipped, ~15% processing, ~15% new, ~10% cancelled
                ...array_fill(0, 8, 'delivered'),
                ...array_fill(0, 4, 'shipped'),
                ...array_fill(0, 3, 'processing'),
                ...array_fill(0, 3, 'new'),
                ...array_fill(0, 2, 'cancelled'),
            ]),
            'shipping_price' => $this->faker->randomFloat(2, 100, 500),
            'shipping_method' => $this->faker->randomElement(['free', 'flat', 'flat_rate', 'flat_rate_per_item']),
            'notes' => $this->faker->realText(100),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-5 month', 'now'),
        ];
    }

    public function configure(): Factory
    {
        return $this->afterCreating(function (Order $order): void {
            $order->address()->save(OrderAddressFactory::new()->make());
        });
    }
}
