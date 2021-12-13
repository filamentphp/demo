<?php

declare(strict_types=1);

namespace Database\Factories\Blog;

use App\Models\Blog\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

final class CategoryFactory extends Factory
{
    /**
     * @var string
     */
    protected $model = Category::class;

    public function definition(): array
    {
        return [
            'name' => $name = $this->faker->unique()->word(),
            'slug' => Str::slug($name),
            'description' => $this->faker->realText(),
            'is_visible' => $this->faker->boolean(),
        ];
    }
}
