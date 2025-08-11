<?php

namespace Database\Factories\Shop;

use App\Models\Shop\Brand;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Brand>
 */
class BrandFactory extends Factory
{
    /**
     * @var string
     */
    protected $model = Brand::class;

    public function definition(): array
    {
        return [
            'name' => $name = $this->faker->unique()->company(),
            'slug' => Str::slug($name),
            'website' => 'https://www.' . $this->faker->domainName(),
            'description' => $this->faker->realText(),
            'is_visible' => $this->faker->boolean(),
            'created_at' => $this->faker->dateTimeBetween('-1 year', '-6 month'),
            'updated_at' => $this->faker->dateTimeBetween('-5 month', 'now'),
        ];
    }
}
