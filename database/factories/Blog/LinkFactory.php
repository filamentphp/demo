<?php

namespace Database\Factories\Blog;

use Database\Factories\Concerns\CanCreateImages;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Blog\Link>
 */
class LinkFactory extends Factory
{
    use CanCreateImages;

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
            'image' => $this->createImage('https://source.unsplash.com/random/1280x720/?img=1'),
        ];
    }
}
