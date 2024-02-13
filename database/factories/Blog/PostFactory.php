<?php

namespace Database\Factories\Blog;

use App\Models\Blog\Post;
use Database\Seeders\LocalImages;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Blog\Post>
 */
class PostFactory extends Factory
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
            'published_at' => $this->faker->dateTimeBetween('-6 month', '+1 month'),
            'created_at' => $this->faker->dateTimeBetween('-1 year', '-6 month'),
            'updated_at' => $this->faker->dateTimeBetween('-5 month', 'now'),
        ];
    }

    public function configure(): PostFactory
    {
        return $this->afterCreating(function (Post $product) {
            $product
                ->addMedia(LocalImages::getRandomFile(LocalImages::SIZE_200x200))
                ->preservingOriginal()
                ->toMediaCollection('post-images');
        });
    }
}
