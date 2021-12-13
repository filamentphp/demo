<?php

declare(strict_types=1);

namespace Database\Factories\Blog;

use App\Models\Blog\Post;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

final class PostFactory extends Factory
{
    /**
     * @var string
     */
    protected $model = Post::class;

    public function definition(): array
    {
        return [
            'title' => $title = $this->faker->unique()->sentence(4),
            'slug' => Str::slug($title),
            'content' => $this->faker->realText(),
            'published_at' => $this->faker->dateTimeBetween('this week', '+1 month'),
        ];
    }
}
