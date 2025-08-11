<?php

namespace Database\Factories\Shop;

use Akaunting\Money\Currency;
use App\Models\Shop\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        return [
            'reference' => 'PAY' . $this->faker->unique()->randomNumber(6),
            'currency' => $this->faker->randomElement(collect(Currency::getCurrencies())->keys()),
            'amount' => $this->faker->randomFloat(2, 100, 2000),
            'provider' => $this->faker->randomElement(['stripe', 'paypal']),
            'method' => $this->faker->randomElement(['credit_card', 'bank_transfer', 'paypal']),
            'created_at' => $this->faker->dateTimeBetween('-1 year', '-6 month'),
            'updated_at' => $this->faker->dateTimeBetween('-5 month', 'now'),
        ];
    }
}
