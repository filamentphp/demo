<?php

use App\Livewire\ProjectPresence;
use App\Models\HR\Project;
use App\Models\User;
use Filament\Panel;
use Illuminate\Support\Facades\Broadcast;
use Livewire\Livewire;

function createProjectForPresenceTest(): Project
{
    return Project::forceCreate([
        'name' => 'Presence test project',
        'slug' => 'presence-test-project-' . str()->random(8),
        'start_date' => '2026-01-01',
    ]);
}

it('authorizes project presence with server authenticated identity only for existing projects', function (): void {
    $project = createProjectForPresenceTest();
    $user = auth()->user();
    $authorizer = Broadcast::connection()->getChannels()->get('project.{project}');

    expect($authorizer)->not->toBeNull()
        ->and($authorizer($user, $project->id))->toBe([
            'id' => (string) $user->id,
            'name' => $user->name,
        ])
        ->and($authorizer($user, $project->id + 100000))->toBeFalse();
});

it('denies project presence when the user cannot access the admin panel', function (): void {
    $project = createProjectForPresenceTest();
    $user = new class extends User
    {
        public function canAccessPanel(Panel $panel): bool
        {
            return false;
        }
    };
    $user->forceFill(['id' => 999, 'name' => 'Unauthorized user']);
    $authorizer = Broadcast::connection()->getChannels()->get('project.{project}');

    expect($authorizer($user, $project->id))->toBeFalse();
});

it('protects the presence component and scopes it to an existing project', function (): void {
    $project = createProjectForPresenceTest();

    Livewire::test(ProjectPresence::class, ['record' => $project])
        ->assertSee('People viewing this project');

    $project->delete();

    Livewire::test(ProjectPresence::class, ['record' => $project])->assertNotFound();

    $project = createProjectForPresenceTest();
    auth()->logout();
    Livewire::test(ProjectPresence::class, ['record' => $project])->assertForbidden();
});
