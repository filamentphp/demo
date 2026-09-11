<?php

namespace App\Filament\Resources\HR\Projects\Pages;

use App\Enums\ProjectStatus;
use App\Filament\Resources\HR\Projects\ProjectResource;
use App\Models\HR\Project;
use App\Models\HR\ProjectRevision;
use App\Models\PageMessage;
use Filament\Resources\Pages\Page;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Livewire\Attributes\On;

class ProjectAttention extends Page
{
    protected static string $resource = ProjectResource::class;

    protected string $view = 'filament.projects.attention';

    protected static ?string $title = 'Project attention';

    /** @return array<string, string> */
    public function getListeners(): array
    {
        return collect($this->projects())
            ->mapWithKeys(fn (Project $project): array => [
                'echo-private:page-chat.' . $this->room($project) . ',PageChatChanged' => 'refreshAttention',
            ])
            ->all();
    }

    #[On('echo-private:projects,ProjectChanged')]
    public function refreshAttention(): void {}

    /** @return EloquentCollection<int, Project> */
    public function projects(): EloquentCollection
    {
        return ProjectResource::getEloquentQuery()
            ->latest('updated_at')
            ->get()
            ->filter(fn (Project $project): bool => ProjectResource::canView($project))
            ->values();
    }

    /** @return Collection<int, ProjectRevision> */
    public function revisionsAwaitingReview(): Collection
    {
        return $this->openRevisions()
            ->where('status', 'pending')
            ->where('requested_reviewer_id', auth()->id())
            ->filter(fn (ProjectRevision $revision): bool => $revision->staleFields() === [])
            ->values();
    }

    /** @return Collection<int, ProjectRevision> */
    public function ownRevisionsNeedingAttention(): Collection
    {
        return $this->openRevisions()
            ->where('author_id', auth()->id())
            ->filter(fn (ProjectRevision $revision): bool => ! $revision->requested_reviewer_id || $revision->status === 'changes_requested' || $revision->staleFields() !== [])
            ->values();
    }

    /** @return Collection<int, array{message: PageMessage, project: Project}> */
    public function unreadMentions(): Collection
    {
        $projects = $this->projects()->keyBy(fn (Project $project): string => $this->room($project));

        if ($projects->isEmpty()) {
            return collect();
        }

        return PageMessage::query()
            ->whereIn('room', $projects->keys())
            ->whereNull('resolved_at')
            ->where(fn ($query) => $query->whereNull('parent_id')->orWhereNotIn('parent_id', PageMessage::query()->select('id')->whereNotNull('resolved_at')))
            ->unreadFor((int) auth()->id())
            ->latest('id')
            ->limit(50)
            ->get()
            ->filter(fn (PageMessage $message): bool => $message->mentionsUser((int) auth()->id()))
            ->take(12)
            ->flatMap(function (PageMessage $message) use ($projects): array {
                $project = $projects->get($message->room);

                return $project instanceof Project ? [['message' => $message, 'project' => $project]] : [];
            })
            ->values();
    }

    /** @return Collection<int, Project> */
    public function blockedProjects(): Collection
    {
        return $this->projects()->filter(fn (Project $project): bool => $project->status === ProjectStatus::OnHold && $project->owner_id === auth()->id())->values();
    }

    public function projectUrl(Project $project, ?int $thread = null, ?int $revision = null): string
    {
        return ProjectResource::getUrl('view', ['record' => $project] + ($thread ? ['chat' => $thread] : []) + ($revision ? ['project-tab' => 'project-revisions'] : [])) . ($revision ? '#revision-' . $revision : '');
    }

    /** @return Collection<int, ProjectRevision> */
    private function openRevisions(): Collection
    {
        $projects = $this->projects();

        return ProjectRevision::query()
            ->with(['project', 'author', 'requestedReviewer'])
            ->whereIn('project_id', $projects->modelKeys())
            ->whereIn('status', ['pending', 'changes_requested'])
            ->latest('updated_at')
            ->get();
    }

    private function room(Project $project): string
    {
        return hash('sha256', '/projects/' . $project->getKey());
    }
}
