<?php

use App\Filament\Resources\Blog\Posts\Pages\ListPosts;
use App\Filament\Resources\Blog\Posts\PostResource;
use App\Models\Blog\Post;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\Testing\TestAction;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

it('can render the list page', function () {
    $records = Post::factory()->count(3)->create();

    Livewire::test(ListPosts::class)
        ->assertOk()
        ->assertCanSeeTableRecords($records);
});

it('names post image links without disabling empty image cells', function () {
    Storage::fake('public');

    $records = Post::factory()->createMany([
        ['title' => 'Accessibility "with" & image', 'published_at' => null],
        ['title' => 'Accessibility without an image'],
    ]);
    $records[1]->clearMediaCollection('post-images');

    $page = Livewire::test(ListPosts::class)
        ->searchTable('Accessibility')
        ->assertCanSeeTableRecords($records);

    $document = new DOMDocument;
    @$document->loadHTML($page->html());
    $xpath = new DOMXPath($document);
    $url = PostResource::getUrl('view', ['record' => $records[0]]);
    $images = $xpath->query('//a[@href="' . $url . '"]//img');

    expect($images->length)->toBeGreaterThan(0);
    expect($images->item(0)->getAttribute('alt'))->toBe('View Accessibility "with" & image');

    $emptyImageLinks = $xpath->query('//a[@href="' . PostResource::getUrl('view', ['record' => $records[1]]) . '"][.//div[contains(@class, "fi-ta-image")] and not(.//img)]');
    expect($emptyImageLinks->length)->toBe(1);

    $statusLinks = $xpath->query('//a[@href="' . $url . '"][normalize-space(.)="Draft"]');
    expect($statusLinks->length)->toBe(1);
    expect($statusLinks->item(0)->hasAttribute('aria-label'))->toBeFalse();
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
