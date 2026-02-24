<?php

use App\Filament\Resources\Shop\Products\Pages\EditProduct;
use App\Filament\Resources\Shop\Products\RelationManagers\CommentsRelationManager;
use App\Models\Comment;
use App\Models\Shop\Customer;
use App\Models\Shop\Product;
use Filament\Actions\DeleteAction;
use Filament\Actions\Testing\TestAction;
use Livewire\Livewire;

it('can render the relation manager', function () {
    $product = Product::factory()->create();

    Livewire::test(CommentsRelationManager::class, [
        'ownerRecord' => $product,
        'pageClass' => EditProduct::class,
    ])->assertOk();
});

it('can create a comment and sends database notification', function () {
    $product = Product::factory()->create();
    $customer = Customer::factory()->create();

    Livewire::test(CommentsRelationManager::class, [
        'ownerRecord' => $product,
        'pageClass' => EditProduct::class,
    ])
        ->callAction(TestAction::make('create')->table(), [
            'title' => 'Great product',
            'customer_id' => $customer->id,
            'content' => 'I love this product!',
            'is_visible' => true,
        ])
        ->assertNotified();

    $this->assertDatabaseHas(Comment::class, [
        'commentable_id' => $product->id,
        'commentable_type' => $product->getMorphClass(),
        'title' => 'Great product',
        'customer_id' => $customer->id,
    ]);

    $user = auth()->user();
    expect($user->notifications)->toHaveCount(1);
    expect($user->notifications->first()->data['title'])->toBe('New comment');
});

it('does not actually delete a comment', function () {
    $product = Product::factory()->create();
    $customer = Customer::factory()->create();
    $comment = $product->comments()->create([
        'title' => 'Undeletable Comment',
        'customer_id' => $customer->id,
        'content' => 'This should persist',
        'is_visible' => true,
    ]);

    Livewire::test(CommentsRelationManager::class, [
        'ownerRecord' => $product,
        'pageClass' => EditProduct::class,
    ])
        ->callAction(TestAction::make(DeleteAction::class)->table($comment))
        ->assertNotified();

    $this->assertDatabaseHas(Comment::class, [
        'id' => $comment->id,
    ]);
});

it('can edit a comment', function () {
    $product = Product::factory()->create();
    $customer = Customer::factory()->create();
    $comment = $product->comments()->create([
        'title' => 'Original Title',
        'customer_id' => $customer->id,
        'content' => 'Original content',
        'is_visible' => true,
    ]);

    Livewire::test(CommentsRelationManager::class, [
        'ownerRecord' => $product,
        'pageClass' => EditProduct::class,
    ])
        ->callAction(TestAction::make('edit')->table($comment), [
            'title' => 'Updated Title',
        ])
        ->assertNotified();

    $this->assertDatabaseHas(Comment::class, [
        'id' => $comment->id,
        'title' => 'Updated Title',
    ]);
});
