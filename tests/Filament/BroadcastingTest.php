<?php

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;
use Pusher\Pusher;

beforeEach(function (): void {
    Broadcast::connection('reverb')->setPusher(new Pusher('test-key', 'test-secret', 'test-app'));
    config()->set('broadcasting.default', 'reverb');
});

it('authorizes the users own private notification channel', function (): void {
    $channel = 'private-App.Models.User.' . auth()->id();

    $this->postJson('/broadcasting/auth', [
        'socket_id' => '123.456',
        'channel_name' => $channel,
    ])
        ->assertSuccessful()
        ->assertExactJson([
            'auth' => 'test-key:' . hash_hmac('sha256', '123.456:' . $channel, 'test-secret'),
        ]);
});

it('rejects another users private notification channel', function (): void {
    $otherUser = User::factory()->create();

    $this->postJson('/broadcasting/auth', [
        'socket_id' => '123.456',
        'channel_name' => 'private-App.Models.User.' . $otherUser->id,
    ])->assertForbidden();
});

it('rejects unauthenticated notification subscriptions', function (): void {
    $userId = auth()->id();

    auth()->logout();

    $this->postJson('/broadcasting/auth', [
        'socket_id' => '123.456',
        'channel_name' => 'private-App.Models.User.' . $userId,
    ])->assertForbidden();
});
