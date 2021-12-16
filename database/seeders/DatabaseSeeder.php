<?php

namespace Database\Seeders;

use App\Models\Blog\Author;
use App\Models\Blog\Category as BlogCategory;
use App\Models\Blog\Post;
use App\Models\Shop\Brand;
use App\Models\Shop\Category as ShopCategory;
use App\Models\Shop\Customer;
use App\Models\Shop\Discount;
use App\Models\Shop\Order;
use App\Models\Shop\OrderItem;
use App\Models\Shop\Product;
use App\Models\Shop\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::factory()->create([
            'name' => 'Demo User',
            'email' => 'admin@filamentadmin.com',
        ]);
        $this->command->info('Admin user created.');

        // Blog
        $blogCategories = BlogCategory::factory()->count(20)->create();
        $this->command->info('Blog categories created.');
        Author::factory()->count(20)
            ->has(
                Post::factory()->count(10)
                    ->state(fn (array $attributes, Author $author) => ['blog_category_id' => $blogCategories->random(1)->first()->id]),
                'posts'
            )
            ->create();
        $this->command->info('Blog authors and posts created.');

        // Shop
        $categories = ShopCategory::factory()->count(20)
            ->has(
                ShopCategory::factory()->count(3),
                'children'
            )->create();
        $this->command->info('Shop categories created.');
        $brands = Brand::factory()->count(20)->create();
        $this->command->info('Shop brands created.');
        $customers = Customer::factory()->count(1000)->create();
        $this->command->info('Shop customers created.');
        Discount::factory()->count(20)->create();
        $this->command->info('Shop discounts created.');
        $products = Product::factory()->count(50)
            ->sequence(fn ($sequence) => ['shop_brand_id' => $brands->random(1)->first()->id])
            ->hasAttached($categories->random(rand(3, 6)), ['created_at' => now(), 'updated_at' => now()])
            ->has(
                Review::factory()->count(rand(10, 20))
                    ->state(fn (array $attributes, Product $product) => ['shop_customer_id' => $customers->random(1)->first()->id]),
                'reviews'
            )
            ->create();
        $this->command->info('Shop products created.');
        Order::factory()->count(1000)
            ->sequence(fn ($sequence) => ['shop_customer_id' => $customers->random(1)->first()->id])
            ->has(
                OrderItem::factory()->count(rand(2, 5))
                    ->state(fn (array $attributes, Order $order) => ['shop_product_id' => $products->random(1)->first()->id]),
                'items'
            )
            ->create();
        $this->command->info('Shop orders created.');
    }
}
