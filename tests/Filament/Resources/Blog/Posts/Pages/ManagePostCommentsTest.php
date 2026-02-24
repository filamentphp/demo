<?php

use App\Filament\Resources\Blog\Posts\Pages\ManagePostComments;
use App\Models\Blog\Post;
use App\Models\Comment;
use App\Models\Shop\Customer;
use Filament\Actions\DeleteAction;
use Filament\Actions\Testing\TestAction;
use Livewire\Livewire;

it('can render the comments page', function () {
    $post = Post::factory()->create();
    $customer = Customer::factory()->create();
    $post->comments()->create([
        'title' => 'Test Comment',
        'customer_id' => $customer->id,
        'content' => 'Test content',
        'is_visible' => true,
    ]);

    Livewire::test(ManagePostComments::class, ['record' => $post->getRouteKey()])
        ->assertOk();
});

it('can create a comment', function () {
    $post = Post::factory()->create();
    $customer = Customer::factory()->create();

    Livewire::test(ManagePostComments::class, ['record' => $post->getRouteKey()])
        ->callAction(TestAction::make('create')->table(), [
            'title' => 'New Comment',
            'customer_id' => $customer->id,
            'content' => 'Great post!',
            'is_visible' => true,
        ])
        ->assertNotified();

    $this->assertDatabaseHas(Comment::class, [
        'commentable_id' => $post->id,
        'commentable_type' => $post->getMorphClass(),
        'title' => 'New Comment',
        'customer_id' => $customer->id,
    ]);
});

it('can view a comment', function () {
    $post = Post::factory()->create();
    $customer = Customer::factory()->create();
    $comment = $post->comments()->create([
        'title' => 'Viewable Comment',
        'customer_id' => $customer->id,
        'content' => 'Some content',
        'is_visible' => true,
    ]);

    Livewire::test(ManagePostComments::class, ['record' => $post->getRouteKey()])
        ->callAction(TestAction::make('view')->table($comment))
        ->assertOk();
});

it('can edit a comment', function () {
    $post = Post::factory()->create();
    $customer = Customer::factory()->create();
    $comment = $post->comments()->create([
        'title' => 'Original Title',
        'customer_id' => $customer->id,
        'content' => 'Original content',
        'is_visible' => true,
    ]);

    Livewire::test(ManagePostComments::class, ['record' => $post->getRouteKey()])
        ->callAction(TestAction::make('edit')->table($comment), [
            'title' => 'Updated Title',
        ])
        ->assertNotified();

    $this->assertDatabaseHas(Comment::class, [
        'id' => $comment->id,
        'title' => 'Updated Title',
    ]);
});

it('does not actually delete a comment', function () {
    $post = Post::factory()->create();
    $customer = Customer::factory()->create();
    $comment = $post->comments()->create([
        'title' => 'Undeletable Comment',
        'customer_id' => $customer->id,
        'content' => 'This should persist',
        'is_visible' => true,
    ]);

    Livewire::test(ManagePostComments::class, ['record' => $post->getRouteKey()])
        ->callAction(TestAction::make(DeleteAction::class)->table($comment))
        ->assertNotified();

    $this->assertDatabaseHas(Comment::class, [
        'id' => $comment->id,
    ]);
});
