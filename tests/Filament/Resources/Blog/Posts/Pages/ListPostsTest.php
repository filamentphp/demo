<?php

use App\Filament\Resources\Blog\Posts\Pages\ListPosts;
use App\Models\Blog\Post;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\Testing\TestAction;
use Livewire\Livewire;

it('can render the list page', function () {
    $records = Post::factory()->count(3)->create();

    Livewire::test(ListPosts::class)
        ->assertOk()
        ->assertCanSeeTableRecords($records);
});

it('can toggle publish on a draft post', function () {
    $record = Post::factory()->create(['published_at' => null]);

    Livewire::test(ListPosts::class)
        ->callAction(TestAction::make('toggle_publish')->table($record))
        ->assertNotified();

    $record->refresh();
    expect($record->published_at)->not->toBeNull();
});

it('can toggle publish on a published post', function () {
    $record = Post::factory()->create(['published_at' => now()->subDay()]);

    Livewire::test(ListPosts::class)
        ->callAction(TestAction::make('toggle_publish')->table($record))
        ->assertNotified();

    $record->refresh();
    expect($record->published_at)->toBeNull();
});

it('can delete a post', function () {
    $record = Post::factory()->create();

    Livewire::test(ListPosts::class)
        ->callAction(TestAction::make(DeleteAction::class)->table($record))
        ->assertNotified();
});

it('can bulk delete posts', function () {
    $records = Post::factory()->count(3)->create();

    Livewire::test(ListPosts::class)
        ->selectTableRecords($records)
        ->callAction(TestAction::make(DeleteBulkAction::class)->table()->bulk())
        ->assertNotified();
});

it('can filter posts by published date range', function () {
    $oldPost = Post::factory()->create(['published_at' => now()->subMonths(3)]);
    $recentPost = Post::factory()->create(['published_at' => now()->subDay()]);

    Livewire::test(ListPosts::class)
        ->filterTable('published_at', [
            'published_from' => now()->subWeek()->toDateString(),
            'published_until' => now()->toDateString(),
        ])
        ->assertCanSeeTableRecords([$recentPost])
        ->assertCanNotSeeTableRecords([$oldPost]);
});
