<?php

namespace Database\Factories\Shop;

use App\Models\Shop\OrderItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OrderItem>
 */
class OrderItemFactory extends Factory
{
    /**
     * @var string
     */
    protected $model = OrderItem::class;

    public function definition(): array
    {
        return [
            'qty' => $this->faker->numberBetween(1, 10),
            'unit_price' => $this->faker->randomFloat(2, 100, 500),
        ];
    }
}
