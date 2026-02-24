<?php

namespace Database\Factories\HR;

use App\Enums\ExpenseCategory;
use App\Enums\ExpenseStatus;
use App\Models\HR\Expense;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Expense>
 */
class ExpenseFactory extends Factory
{
    /**
     * @var string
     */
    protected $model = Expense::class;

    public function definition(): array
    {
        $status = $this->faker->randomElement(ExpenseStatus::cases());

        $createdAt = $this->faker->dateTimeBetween('-12 months', 'now');

        return [
            'expense_number' => 'EXP-' . str_pad((string) $this->faker->unique()->numberBetween(1, 999999), 6, '0', STR_PAD_LEFT),
            'status' => $status,
            'category' => $this->faker->randomElement(ExpenseCategory::cases()),
            'description' => $this->faker->sentence(),
            'total_amount' => 0,
            'currency' => $this->faker->randomElement(['USD', 'USD', 'USD', 'EUR', 'GBP', 'CAD']),
            'submitted_at' => in_array($status, [ExpenseStatus::Submitted, ExpenseStatus::Approved, ExpenseStatus::Reimbursed])
                ? $this->faker->dateTimeBetween($createdAt, 'now')
                : null,
            'approved_at' => in_array($status, [ExpenseStatus::Approved, ExpenseStatus::Reimbursed])
                ? $this->faker->dateTimeBetween($createdAt, 'now')
                : null,
            'notes' => $this->faker->optional(0.3)->sentence(),
            'created_at' => $createdAt,
            'updated_at' => $this->faker->dateTimeBetween($createdAt, 'now'),
        ];
    }
}
