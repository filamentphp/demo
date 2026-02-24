<?php

use App\Filament\Resources\Blog\Posts\Pages\EditPost;
use App\Models\Blog\Author;
use App\Models\Blog\Post;
use App\Models\Blog\PostCategory;
use Illuminate\Support\Str;
use Livewire\Livewire;

it('can render the edit page', function () {
    $record = Post::factory()->create();

    Livewire::test(EditPost::class, ['record' => $record->getRouteKey()])
        ->assertOk();
});

it('can update a record', function () {
    $author = Author::factory()->create();
    $category = PostCategory::factory()->create();
    $record = Post::factory()->create([
        'author_id' => $author->id,
        'post_category_id' => $category->id,
    ]);
    $newData = Post::factory()->make();

    Livewire::test(EditPost::class, ['record' => $record->getRouteKey()])
        ->fillForm([
            'title' => $newData->title,
            'content' => $newData->content,
        ])
        ->call('save')
        ->assertHasNoFormErrors()
        ->assertNotified();

    $this->assertDatabaseHas(Post::class, [
        'id' => $record->id,
        'title' => $newData->title,
    ]);
});

it('validates the form data', function (array $data, array $errors) {
    $author = Author::factory()->create();
    $category = PostCategory::factory()->create();
    $record = Post::factory()->create([
        'author_id' => $author->id,
        'post_category_id' => $category->id,
    ]);
    $newData = Post::factory()->make();

    Livewire::test(EditPost::class, ['record' => $record->getRouteKey()])
        ->fillForm([
            'title' => $newData->title,
            'content' => $newData->content,
            ...$data,
        ])
        ->call('save')
        ->assertHasFormErrors($errors)
        ->assertNotNotified();
})->with([
    '`title` is required' => [['title' => null], ['title' => 'required']],
    '`title` is max 255 characters' => [['title' => Str::random(256)], ['title' => 'max']],
    '`author_id` is required' => [['author_id' => null], ['author_id' => 'required']],
    '`post_category_id` is required' => [['post_category_id' => null], ['post_category_id' => 'required']],
]);
