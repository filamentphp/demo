<?php

declare(strict_types=1);

namespace Database\Factories\Blog;

use App\Models\Blog\Author;
use Illuminate\Database\Eloquent\Factories\Factory;

final class AuthorFactory extends Factory
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
        ];
    }
}
