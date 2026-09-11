<?php

use App\Enums\ProjectStatus;
use App\Filament\Resources\HR\Projects\Pages\ProjectAttention;
use App\Filament\Resources\HR\Projects\ProjectResource;
use App\Models\HR\Project;
use App\Models\HR\ProjectRevision;
use App\Models\PageMessage;
use App\Models\PageMessageRead;
use App\Models\User;
use Livewire\Livewire;

it('shows only the project attention categories that apply to the current user', function (): void {
    $user = auth()->user();
    $other = User::factory()->create(['name' => 'Revision Author']);
    $reviewProject = Project::factory()->create(['name' => 'Review project', 'status' => ProjectStatus::Active]);
    $ownProject = Project::factory()->create(['name' => 'Own revision project', 'status' => ProjectStatus::Active]);
    $blockedProject = Project::factory()->create(['name' => 'Blocked project', 'status' => ProjectStatus::OnHold, 'owner_id' => $user->id]);
    Project::factory()->create(['name' => 'Someone else’s blocked project', 'status' => ProjectStatus::OnHold, 'owner_id' => $other->id]);

    $this->actingAs($other);
    $reviewRevision = ProjectRevision::propose($reviewProject, $other, ['name' => 'Reviewed name'], 'Needs a second pair of eyes', requestedReviewer: $user);
    $this->actingAs($user);
    $ownRevision = ProjectRevision::propose($ownProject, $user, ['name' => 'New own name'], 'My revision', requestedReviewer: $other);
    $this->actingAs($other);
    $ownRevision->requestChanges($other, 1, 'Add more detail');
    $this->actingAs($user);

    $page = Livewire::test(ProjectAttention::class)
        ->assertSee('Awaiting review')
        ->assertSee('Review project')
        ->assertSee('Proposed by Revision Author')
        ->assertSee('Your revisions')
        ->assertSee('Own revision project')
        ->assertSee('Changes requested')
        ->assertSee('Blocked projects')
        ->assertSee('Blocked project')
        ->assertDontSee('Someone else’s blocked project');

    expect($page->instance()->revisionsAwaitingReview()->modelKeys())->toBe([$reviewRevision->id])
        ->and($page->instance()->ownRevisionsNeedingAttention()->modelKeys())->toBe([$ownRevision->id])
        ->and($page->instance()->blockedProjects()->modelKeys())->toBe([$blockedProject->id]);
});

it('shows only unread unresolved project mentions and links to their threads', function (): void {
    $user = auth()->user();
    $author = User::factory()->create(['name' => 'Mention Author']);
    $project = Project::factory()->create(['name' => 'Mentioned project']);
    $room = hash('sha256', '/projects/' . $project->id);
    $body = '<p><span data-type="mention" data-id="user:' . $user->id . '" data-label="User" data-char="@">@User</span></p>';
    $unread = PageMessage::query()->create(['room' => $room, 'user_id' => $author->id, 'body' => $body, 'body_format' => 'html']);
    $read = PageMessage::query()->create(['room' => $room, 'user_id' => $author->id, 'body' => $body, 'body_format' => 'html']);
    PageMessageRead::query()->create(['user_id' => $user->id, 'page_message_id' => $read->id]);
    PageMessage::query()->create(['room' => $room, 'user_id' => $author->id, 'body' => $body, 'body_format' => 'html', 'resolved_at' => now()]);
    $resolvedThread = PageMessage::query()->create(['room' => $room, 'user_id' => $author->id, 'body' => 'Resolved discussion', 'resolved_at' => now()]);
    PageMessage::query()->create(['room' => $room, 'parent_id' => $resolvedThread->id, 'user_id' => $author->id, 'body' => $body, 'body_format' => 'html']);

    $page = Livewire::test(ProjectAttention::class)
        ->assertSee('Mentioned project')
        ->assertSee('Mention Author mentioned you')
        ->assertSee(ProjectResource::getUrl('view', ['record' => $project, 'chat' => $unread->id]), false);

    expect($page->instance()->unreadMentions())->toHaveCount(1)
        ->and(array_keys($page->instance()->getListeners()))->toContain('echo-private:page-chat.' . $room . ',PageChatChanged');
});

it('refreshes from project and authorized project chat broadcasts', function (): void {
    $project = Project::factory()->create(['name' => 'Initially active', 'status' => ProjectStatus::Active, 'owner_id' => auth()->id()]);
    $page = Livewire::test(ProjectAttention::class)->assertDontSee('Initially active');

    $project->update(['status' => ProjectStatus::OnHold]);

    $page->dispatch('echo-private:projects,ProjectChanged', ['projectId' => $project->id])
        ->assertSee('Initially active')
        ->dispatch('echo-private:page-chat.' . hash('sha256', '/projects/' . $project->id) . ',PageChatChanged')
        ->assertSee('Initially active');
});
