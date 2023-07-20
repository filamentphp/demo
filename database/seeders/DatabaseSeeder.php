<?php

namespace Database\Seeders;

use App\Filament\Resources\Shop\OrderResource;
use App\Models\Address;
use App\Models\Blog\Author;
use App\Models\Blog\Category as BlogCategory;
use App\Models\Blog\Post;
use App\Models\Comment;
use App\Models\Shop\Brand;
use App\Models\Shop\Category as ShopCategory;
use App\Models\Shop\Customer;
use App\Models\Shop\Order;
use App\Models\Shop\OrderItem;
use App\Models\Shop\Payment;
use App\Models\Shop\Product;
use App\Models\User;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    const IMAGE_URL = 'https://source.unsplash.com/random/200x200/?img=1';

    public function run(): void
    {
        // Clear images
        Storage::deleteDirectory('public');

        // Admin
        $user = User::factory()->create([
            'name' => 'Demo User',
            'email' => 'admin@filamentphp.com',
        ]);
        $this->command->info('Admin user created.');

        // Shop
        $categories = ShopCategory::factory()->count(20)
            ->has(
                ShopCategory::factory()->count(3),
                'children'
            )->create();
        $this->command->info('Shop categories created.');

        $brands = Brand::factory()->count(20)
            ->has(Address::factory()->count(rand(1, 3)))
            ->create();
        $this->command->info('Shop brands created.');

        $customers = Customer::factory()->count(1000)
            ->has(Address::factory()->count(rand(1, 3)))
            ->create();
        $this->command->info('Shop customers created.');

        $products = Product::factory()->count(50)
            ->sequence(fn ($sequence) => ['shop_brand_id' => $brands->random(1)->first()->id])
            ->hasAttached($categories->random(rand(3, 6)), ['created_at' => now(), 'updated_at' => now()])
            ->has(
                Comment::factory()->count(rand(10, 20))
                    ->state(fn (array $attributes, Product $product) => ['customer_id' => $customers->random(1)->first()->id]),
            )
            ->create();
        $this->command->info('Shop products created.');

        $orders = Order::factory()->count(1000)
            ->sequence(fn ($sequence) => ['shop_customer_id' => $customers->random(1)->first()->id])
            ->has(Payment::factory()->count(rand(1, 3)))
            ->has(
                OrderItem::factory()->count(rand(2, 5))
                    ->state(fn (array $attributes, Order $order) => ['shop_product_id' => $products->random(1)->first()->id]),
                'items'
            )
            ->create();

        foreach ($orders->random(rand(5, 8)) as $order) {
            Notification::make()
                ->title('New order')
                ->icon('heroicon-m-shopping-bag')
                ->body("{$order->customer->name} ordered {$order->items->count()} products.")
                ->actions([
                    Action::make('View')
                        ->url(OrderResource::getUrl('edit', ['record' => $order])),
                ])
                ->sendToDatabase($user);
        }
        $this->command->info('Shop orders created.');

        // Blog
        $blogCategories = BlogCategory::factory()->count(20)->create();
        $this->command->info('Blog categories created.');

        Author::factory()->count(20)
            ->has(
                Post::factory()->count(5)
                    ->has(
                        Comment::factory()->count(rand(5, 10))
                            ->state(fn (array $attributes, Post $post) => ['customer_id' => $customers->random(1)->first()->id]),
                    )
                    ->state(fn (array $attributes, Author $author) => ['blog_category_id' => $blogCategories->random(1)->first()->id]),
                'posts'
            )
            ->create();
        $this->command->info('Blog authors and posts created.');
    }
}
