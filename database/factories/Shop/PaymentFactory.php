<?php

namespace Database\Factories\Shop;

use App\Enums\CurrencyCode;
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
            'currency' => $this->faker->randomElement(CurrencyCode::cases())->value,
            'amount' => $this->faker->randomFloat(2, 100, 2000),
            'provider' => $this->faker->randomElement(['stripe', 'paypal']),
            'method' => $this->faker->randomElement([
                // Weighted: ~40% credit card, ~20% paypal, ~15% bank transfer, ~15% apple pay, ~10% google pay
                ...array_fill(0, 8, 'credit_card'),
                ...array_fill(0, 4, 'paypal'),
                ...array_fill(0, 3, 'bank_transfer'),
                ...array_fill(0, 3, 'apple_pay'),
                ...array_fill(0, 2, 'google_pay'),
            ]),
            'created_at' => $this->faker->dateTimeBetween('-1 year', '-6 month'),
            'updated_at' => $this->faker->dateTimeBetween('-5 month', 'now'),
        ];
    }
}
