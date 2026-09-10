<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Context;

class ProjectActivity extends Model
{
    /** @var array<string, string> */
    protected $casts = ['changes' => 'array'];

    /** @param array<string, array{old: ?string, new: ?string}> $changes */
    public static function record(int $projectId, string $event, ?string $subject = null, array $changes = []): void
    {
        $actor = Context::getHidden('project_history_actor', auth()->user());
        $interface = Context::getHidden('project_history_interface', $actor ? 'panel' : 'system');

        static::query()->create([
            'project_id' => $projectId,
            'user_id' => $actor?->getKey(),
            'actor_name' => $actor->name ?? 'System',
            'interface' => $interface,
            'event' => $event,
            'subject' => $subject,
            'changes' => $changes,
        ]);
    }

    public static function recordProjectUpdate(Project $project): void
    {
        $changes = [];

        foreach (['name', 'slug', 'description', 'department_id', 'status', 'priority', 'color', 'start_date', 'end_date', 'budget', 'spent', 'estimated_hours', 'actual_hours', 'plan'] as $field) {
            if (! $project->wasChanged($field)) {
                continue;
            }

            $changes[$field] = in_array($field, ['description', 'plan'])
                ? ['old' => null, 'new' => 'Updated']
                : [
                    'old' => static::formatValue($field, $project->getRawOriginal($field)),
                    'new' => static::formatValue($field, $project->getAttributes()[$field] ?? null),
                ];
        }

        if ($changes !== []) {
            static::record($project->id, 'project_updated', changes: $changes);
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
            'status', 'priority' => str((string) $value)->replace('_', ' ')->ucfirst()->toString(),
            'budget', 'spent' => '$' . number_format((float) $value, 2),
            'estimated_hours', 'actual_hours' => number_format((float) $value, 1) . ' hours',
            default => (string) $value,
        };
    }
}
