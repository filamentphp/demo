<?php

declare(strict_types=1);

namespace Database\Factories\Shop;

use App\Models\Shop\Review;
use Illuminate\Database\Eloquent\Factories\Factory;

final class ReviewFactory extends Factory
{
    /**
     * @var string
     */
    protected $model = Review::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->catchPhrase(),
            'content' => $this->faker->text(),
            'rating' => $this->faker->numberBetween(1, 5),
            'is_visible' => $this->faker->boolean(),
            'is_recommended' => $this->faker->boolean(),
        ];
    }
}
