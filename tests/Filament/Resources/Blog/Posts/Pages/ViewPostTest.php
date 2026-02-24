<?php

use App\Filament\Resources\Blog\Posts\Pages\ViewPost;
use App\Models\Blog\Post;
use Livewire\Livewire;

it('can render the view page', function () {
    $record = Post::factory()->create();

    Livewire::test(ViewPost::class, ['record' => $record->getRouteKey()])
        ->assertOk();
});

it('can quick publish a draft post', function () {
    $record = Post::factory()->create(['published_at' => null]);

    Livewire::test(ViewPost::class, ['record' => $record->getRouteKey()])
        ->callAction('quick_publish')
        ->assertNotified();

    $record->refresh();
    expect($record->published_at)->not->toBeNull();
});

it('quick publish is hidden for published posts', function () {
    $record = Post::factory()->create(['published_at' => now()->subDay()]);

    Livewire::test(ViewPost::class, ['record' => $record->getRouteKey()])
        ->assertActionHidden('quick_publish');
});

it('can unpublish a published post', function () {
    $record = Post::factory()->create(['published_at' => now()->subDay()]);

    Livewire::test(ViewPost::class, ['record' => $record->getRouteKey()])
        ->callAction('unpublish')
        ->assertNotified();

    $record->refresh();
    expect($record->published_at)->toBeNull();
});

it('unpublish is hidden for draft posts', function () {
    $record = Post::factory()->create(['published_at' => null]);

    Livewire::test(ViewPost::class, ['record' => $record->getRouteKey()])
        ->assertActionHidden('unpublish');
});
