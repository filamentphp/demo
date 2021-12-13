<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Blog\Author;
use App\Models\Blog\Category;
use App\Models\Blog\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

final class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::factory()->create([
            'name' => 'Filament Admin',
            'email' => 'admin@filamentadmin.com',
        ]);

        // Blog
        Category::factory()->count(20)->create();
        $categoryIds = Category::where('is_visible', true)->pluck('id');
        Author::factory()->count(20)
            ->has(
                Post::factory()
                    ->count(10)
                    ->state(fn (array $attributes, Author $author) =>  ['blog_author_id' => $author->id, 'blog_category_id' => $categoryIds->random()]),
                'posts'
            )
            ->create();
    }
}
