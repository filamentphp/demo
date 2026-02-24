<?php

use App\Filament\Resources\Blog\Posts\Pages\CreatePost;
use App\Models\Blog\Author;
use App\Models\Blog\Post;
use App\Models\Blog\PostCategory;
use Illuminate\Support\Str;
use Livewire\Livewire;

it('can render the create page', function () {
    Livewire::test(CreatePost::class)
        ->assertOk();
});

it('can create a record', function () {
    $data = Post::factory()->make();
    $author = Author::factory()->create();
    $category = PostCategory::factory()->create();

    Livewire::test(CreatePost::class)
        ->fillForm([
            'title' => $data->title,
            'content' => $data->content,
            'author_id' => $author->id,
            'post_category_id' => $category->id,
        ])
        ->call('create')
        ->assertNotified()
        ->assertRedirect();

    $this->assertDatabaseHas(Post::class, ['title' => $data->title]);
});

it('validates the form data', function (array $data, array $errors) {
    $author = Author::factory()->create();
    $category = PostCategory::factory()->create();
    $newData = Post::factory()->make();

    Livewire::test(CreatePost::class)
        ->fillForm([
            'title' => $newData->title,
            'content' => $newData->content,
            'author_id' => $author->id,
            'post_category_id' => $category->id,
            ...$data,
        ])
        ->call('create')
        ->assertHasFormErrors($errors)
        ->assertNotNotified()
        ->assertNoRedirect();
})->with([
    '`title` is required' => [['title' => null], ['title' => 'required']],
    '`title` is max 255 characters' => [['title' => Str::random(256)], ['title' => 'max']],
    '`author_id` is required' => [['author_id' => null], ['author_id' => 'required']],
    '`post_category_id` is required' => [['post_category_id' => null], ['post_category_id' => 'required']],
]);
