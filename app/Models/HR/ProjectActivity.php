<?php

namespace App\Models\HR;

use App\Events\ProjectChanged;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Context;
use Illuminate\Support\Str;

class ProjectActivity extends Model
{
    /** @var array<string, string> */
    protected $casts = [
        'changes' => 'array',
        'raw_changes' => 'array',
        'change_envelope' => 'array',
    ];

    /**
     * @param  array<string, array{old: ?string, new: ?string}>  $changes
     * @param  array<string, array{old: scalar|null, new: scalar|null}>|null  $rawChanges
     * @param  list<string>  $fields
     */
    public static function record(int $projectId, string $event, ?string $subject = null, array $changes = [], ?array $rawChanges = null, string $entityType = 'project', ?int $entityId = null, array $fields = [], ?string $reason = null, ?string $source = null): static
    {
        $envelope = static::makeChangeEnvelope($projectId, $event, $entityType, $entityId, $fields ?: array_keys($changes), $reason, $source);

        $activity = static::query()->create([
            'project_id' => $projectId,
            'user_id' => $envelope['actor']['id'],
            'actor_name' => $envelope['actor']['name'],
            'interface' => $envelope['interface'],
            'event' => $event,
            'subject' => $subject ?? Context::getHidden('project_history_revision_summary'),
            'changes' => $changes,
            'raw_changes' => $rawChanges,
            'revision_id' => $envelope['revision_id'],
            'change_envelope' => $envelope,
        ]);

        ProjectChanged::dispatch($projectId, $envelope);

        return $activity;
    }

    /**
     * Broadcast a lifecycle change without adding a history entry.
     *
     * @param  list<string>  $fields
     * @return array<string, mixed>
     */
    public static function notify(int $projectId, string $event, string $entityType = 'project', ?int $entityId = null, array $fields = [], ?string $reason = null, ?string $source = null): array
    {
        $envelope = static::makeChangeEnvelope($projectId, $event, $entityType, $entityId, $fields, $reason, $source);

        ProjectChanged::dispatch($projectId, $envelope);

        return $envelope;
    }

    /**
     * Only metadata belongs here: never field values, subjects, or model attributes.
     * Reason and source are caller-supplied identifiers, not document content.
     *
     * @param  list<string>  $fields
     * @return array<string, mixed>
     */
    public static function makeChangeEnvelope(int $projectId, string $event, string $entityType = 'project', ?int $entityId = null, array $fields = [], ?string $reason = null, ?string $source = null): array
    {
        $actor = Context::getHidden('project_history_actor', auth()->user());
        $interface = Context::getHidden('project_history_interface', $actor ? 'panel' : 'system');

        return [
            'id' => (string) Str::uuid(),
            'schema_version' => 1,
            'project_id' => $projectId,
            'event' => $event,
            'actor' => ['id' => $actor?->getKey(), 'name' => $actor->name ?? 'System'],
            'interface' => $interface,
            'reason' => $reason ?? Context::getHidden('project_history_reason'),
            'source' => $source ?? Context::getHidden('project_history_source', $interface),
            'occurred_at' => now()->utc()->toISOString(),
            'entity' => ['type' => $entityType, 'id' => $entityId ?? $projectId],
            'fields' => array_values(array_unique($fields)),
            'revision_id' => Context::getHidden('project_history_revision_id', $entityType === 'revision' ? $entityId : null),
        ];
    }

    public static function recordProjectUpdate(Project $project): void
    {
        if (Context::getHidden('project_history_revision_id')) {
            return;
        }

        $changes = [];
        $rawChanges = [];

        foreach (['name', 'slug', 'description', 'department_id', 'owner_id', 'status', 'priority', 'color', 'start_date', 'end_date', 'budget', 'spent', 'estimated_hours', 'actual_hours', 'plan'] as $field) {
            if (! $project->wasChanged($field)) {
                continue;
            }

            $changes[$field] = in_array($field, ['description', 'plan'])
                ? ['old' => null, 'new' => 'Updated']
                : [
                    'old' => static::formatValue($field, $project->getRawOriginal($field)),
                    'new' => static::formatValue($field, $project->getAttributes()[$field] ?? null),
                ];

            if (in_array($field, ['name', 'department_id', 'status', 'priority', 'color', 'start_date', 'end_date', 'budget', 'estimated_hours'], true)) {
                $rawChanges[$field] = [
                    'old' => $project->getRawOriginal($field),
                    'new' => $project->getAttributes()[$field] ?? null,
                ];
            }
        }

        if ($changes !== []) {
            static::record($project->id, 'project_updated', changes: $changes, rawChanges: $rawChanges ?: null);
        } elseif ($project->wasChanged('description_state')) {
            static::notify($project->id, 'project_updated', fields: ['description_state']);
        }

        if ($project->wasChanged('deleted_at') && ! $project->deleted_at) {
            static::record($project->id, 'project_restored');
        }
    }

    protected static function formatValue(string $field, mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        return match ($field) {
            'department_id' => Department::query()->whereKey($value)->value('name') ?? (string) $value,
            'owner_id' => User::query()->whereKey($value)->value('name') ?? (string) $value,
            'status', 'priority' => str((string) $value)->replace('_', ' ')->ucfirst()->toString(),
            'budget', 'spent' => '$' . number_format((float) $value, 2),
            'estimated_hours', 'actual_hours' => number_format((float) $value, 1) . ' hours',
            default => (string) $value,
        };
    }
}
