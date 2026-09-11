<?php

use App\Models\HR\Project;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('projects', fn (User $user): bool => $user->canAccessPanel(Filament::getPanel('admin')));

Broadcast::channel('project.{project}', function (User $user, int $project): array | false {
    if (! $user->canAccessPanel(Filament::getPanel('admin')) || ! Project::query()->whereKey($project)->exists()) {
        return false;
    }

    return [
        'id' => (string) $user->getAuthIdentifier(),
        'name' => $user->name,
    ];
});

Broadcast::channel('page-chat.{room}', fn (User $user): bool => $user->canAccessPanel(Filament::getPanel('admin')));
