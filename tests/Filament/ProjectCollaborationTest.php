<?php

use App\Filament\Resources\HR\Projects\Pages\EditProject;
use App\Forms\Components\ProjectCollaborationPlugin;
use App\Models\HR\Project;
use Livewire\Livewire;

it('protects shared document reads and has no autosave endpoint', function (): void {
    $project = Project::factory()->create();

    $this->getJson('/api/collaboration/projects/' . $project->id)->assertForbidden();
    $this->putJson('/api/collaboration/projects/' . $project->id, [])->assertStatus(405);
});

it('loads saved content and saves the shared state only through the form', function (): void {
    $project = Project::factory()->create([
        'description' => '<p>Before collaboration</p>',
        'start_date' => '2026-01-01',
        'end_date' => null,
    ]);
    $this->withToken(config('collaboration.key'))
        ->getJson('/api/collaboration/projects/' . $project->id)
        ->assertOk()
        ->assertJsonPath('state', null)
        ->assertJsonPath('content.content.0.content.0.text', 'Before collaboration');

    $page = Livewire::test(EditProject::class, ['record' => $project->id])->fillForm([
        'description_state' => 'c2hhcmVkLXN0YXRl',
        'description' => '<p><strong>Together</strong></p>',
    ]);

    expect($project->refresh()->description)->toBe('<p>Before collaboration</p>');
    expect($project->description_state)->toBeNull();
    expect($project->activities()->count())->toBe(1);

    $page->call('save')->assertHasNoFormErrors();

    expect($project->refresh()->description)->toContain('<strong>Together</strong>');
    expect($project->description_state)->toBe('c2hhcmVkLXN0YXRl');
    expect($project->activities()->latest('id')->first())->interface->toBe('panel')
        ->user_id->toBe(auth()->id())
        ->changes->toBe(['description' => ['old' => null, 'new' => 'Updated']]);
});

it('does not save the description when another form field fails validation', function (): void {
    $project = Project::factory()->create(['description' => '<p>Initial description</p>']);
    $page = Livewire::test(EditProject::class, ['record' => $project->id]);
    $page->fillForm(['name' => '', 'description' => '<p>Shared draft</p>'])
        ->call('save')
        ->assertHasFormErrors(['name' => 'required']);

    expect($project->refresh()->description)->toBe('<p>Initial description</p>');
    expect($project->activities()->count())->toBe(1);
});

it('signs document access for the authenticated user and a specific project', function (): void {
    $config = ProjectCollaborationPlugin::configuration(42);
    [$payload, $signature] = explode('.', $config['token']);
    $data = json_decode(base64_decode($payload), true);

    expect($config['document'])->toBe('project.42');
    expect($data)->projectId->toBe(42)->userId->toBe(auth()->id());
    expect($data['expires'])->toBeGreaterThan(now()->timestamp);
    expect($signature)->toBe(hash_hmac('sha256', $payload, config('collaboration.key')));
});
