<?php

namespace Database\Factories\Blog;

use App\Models\Blog\Link;
use Database\Seeders\LocalImages;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Link>
 */
class LinkFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'url' => $this->faker->url(),
            'title' => [
                'en' => Str::title($this->faker->words(asText: true)),
                'es' => Str::title($this->faker->words(asText: true)),
                'nl' => Str::title($this->faker->words(asText: true)),
            ],
            'description' => [
                'en' => $this->faker->sentence(),
                'es' => $this->faker->sentence(),
                'nl' => $this->faker->sentence(),
            ],
            'color' => $this->faker->hexColor(),
        ];
    }

    public function configure(): LinkFactory
    {
        return $this->afterCreating(function (Link $product): void {
            $product
                ->addMedia(LocalImages::getRandomFile(LocalImages::SIZE_1280x720))
                ->preservingOriginal()
                ->toMediaCollection('link-images');
        });
    }
}
