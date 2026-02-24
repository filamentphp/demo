<?php

namespace Database\Factories\Shop;

use App\Models\Shop\OrderAddress;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OrderAddress>
 */
class OrderAddressFactory extends Factory
{
    protected $model = OrderAddress::class;

    public function definition(): array
    {
        return [
            'country' => $this->faker->randomElement(['us', 'gb', 'de', 'fr', 'ca', 'au', 'nl', 'br', 'jp', 'in']),
            'street' => $this->faker->streetAddress(),
            'state' => $this->faker->state(),
            'city' => $this->faker->city(),
            'zip' => $this->faker->postcode(),
        ];
    }
}
