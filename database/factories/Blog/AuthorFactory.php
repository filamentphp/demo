<?php

namespace Database\Factories\Blog;

use App\Models\Blog\Author;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Author>
 */
class AuthorFactory extends Factory
{
    /**
     * @var string
     */
    protected $model = Author::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'bio' => $this->faker->realTextBetween(),
            'github_handle' => $this->faker->userName(),
            'twitter_handle' => $this->faker->userName(),
            'created_at' => $this->faker->dateTimeBetween('-1 year', '-6 month'),
            'updated_at' => $this->faker->dateTimeBetween('-5 month', 'now'),
        ];
    }
}
