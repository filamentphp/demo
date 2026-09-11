<?php

namespace App\Events;

use App\Models\HR\ProjectActivity;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Support\Arr;

class ProjectChanged implements ShouldBroadcastNow, ShouldDispatchAfterCommit
{
    use Dispatchable;

    /** @var array<string, mixed> */
    public array $change;

    /** @param array<string, mixed>|null $change */
    public function __construct(public int $projectId, ?array $change = null)
    {
        $this->change = $change ?? ProjectActivity::makeChangeEnvelope($projectId, 'project_changed');
    }

    /** @return array<string, mixed> */
    public function broadcastWith(): array
    {
        $change = Arr::only($this->change, ['id', 'schema_version', 'project_id', 'event', 'actor', 'interface', 'reason', 'source', 'occurred_at', 'entity', 'fields', 'revision_id']);
        $change['actor'] = Arr::only($change['actor'], ['id', 'name']);
        $change['entity'] = Arr::only($change['entity'], ['type', 'id']);

        return ['projectId' => $this->projectId, 'change' => $change];
    }

    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('projects');
    }
}
