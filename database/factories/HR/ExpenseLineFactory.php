<?php

namespace Database\Factories\HR;

use App\Models\HR\ExpenseLine;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ExpenseLine>
 */
class ExpenseLineFactory extends Factory
{
    /**
     * @var string
     */
    protected $model = ExpenseLine::class;

    public function definition(): array
    {
        $quantity = $this->faker->numberBetween(1, 5);
        $unitPrice = $this->faker->randomFloat(2, 10, 500);

        return [
            'description' => $this->faker->randomElement([
                'Flight ticket', 'Hotel room', 'Taxi fare', 'Team lunch',
                'Office supplies', 'Software license', 'Conference ticket',
                'Training materials', 'Client dinner', 'Parking fee',
            ]),
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'amount' => $quantity * $unitPrice,
            'date' => $this->faker->dateTimeBetween('-12 months', 'now'),
        ];
    }
}
