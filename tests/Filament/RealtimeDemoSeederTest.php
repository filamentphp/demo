<?php

use App\Events\ProjectChanged;
use App\Livewire\PageChat;
use App\Livewire\ProjectHistory;
use App\Models\HR\Project;
use App\Models\HR\ProjectRevision;
use App\Models\PageMessage;
use Database\Seeders\RealtimeDemoSeeder;
use Illuminate\Support\Facades\Event;
use Livewire\Livewire;

it('seeds a coherent scenario without broadcasts and preserves it on reruns', function (): void {
    $this->travelTo(now()->startOfDay()->setHour(12));
    Event::fake([ProjectChanged::class]);
    $unrelated = Project::factory()->create(['name' => 'Existing work']);
    Event::fake([ProjectChanged::class]);
    $this->seed(RealtimeDemoSeeder::class);

    $portal = Project::query()->where('slug', 'northstar-portal')->sole();
    $revision = ProjectRevision::query()->sole();
    expect($portal->owner->email)->toBe('leo@northstar.example')
        ->and($revision->project_id)->toBe($portal->id)
        ->and($revision->author->email)->toBe('maya@northstar.example')
        ->and($revision->nextStepOwner()->email)->toBe('leo@northstar.example')
        ->and($revision->proposed_values['end_date'])->toBe($portal->end_date->copy()->addWeeks(2)->toDateString());
    $agent = PageMessage::query()->where('is_agent', true)->sole();
    expect(Project::query()->count())->toBe(7)
        ->and($portal->tasks()->count())->toBe(3)
        ->and($portal->activities()->latest('id')->first()->changes['status'])->toBe(['old' => 'Active', 'new' => 'On hold'])
        ->and($agent->room)->toBe(hash('sha256', '/projects/' . $portal->id))
        ->and($agent->agentRequest->mentionsAgent())->toBeTrue()
        ->and($agent->body)->toContain('$38,000 of $60,000')
        ->and(PageMessage::query()->whereIn('agent_status', ['pending', 'running'])->count())->toBe(0);
    $empty = Project::query()->where('slug', 'northstar-research')->sole();
    expect($empty->activities()->count())->toBe(0)
        ->and(PageMessage::query()->where('room', hash('sha256', '/projects/' . $empty->id))->count())->toBe(0)
        ->and(Project::query()->where('slug', 'northstar-migration')->sole()->end_date->isPast())->toBeTrue();
    Event::assertNotDispatched(ProjectChanged::class);

    Livewire::test(ProjectHistory::class, ['record' => $portal])
        ->assertSee('Maya Chen')->assertSee('Client portal')->assertSee('On hold');
    Livewire::test(PageChat::class, ['page' => '/projects/' . $portal->id . '/edit'])
        ->call('openThread', $agent->parent_id)
        ->assertSee('Sam Okafor')->assertSee('Agent active')->assertSee('retention policy');

    $count = PageMessage::query()->count();
    $portal->updateQuietly(['name' => 'Presenter edit']);
    $this->seed(RealtimeDemoSeeder::class);
    expect(Project::query()->count())->toBe(7)
        ->and(PageMessage::query()->count())->toBe($count)
        ->and(ProjectRevision::query()->count())->toBe(1)
        ->and($portal->refresh()->name)->toBe('Presenter edit')
        ->and($unrelated->refresh()->name)->toBe('Existing work');
});
