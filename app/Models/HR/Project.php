<?php

namespace App\Models\HR;

use App\Enums\ProjectStatus;
use App\Enums\TaskPriority;
use App\Events\ProjectChanged;
use Database\Factories\HR\ProjectFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    /** @use HasFactory<ProjectFactory> */
    use HasFactory;

    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'projects';

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'status' => ProjectStatus::class,
        'priority' => TaskPriority::class,
        'budget' => 'decimal:2',
        'spent' => 'decimal:2',
        'estimated_hours' => 'decimal:1',
        'actual_hours' => 'decimal:1',
        'start_date' => 'date',
        'end_date' => 'date',
        'plan' => 'array',
    ];

    protected static function booted(): void
    {
        static::created(function (Project $project): void {
            ProjectActivity::record($project->id, 'project_created');
        });

        static::updated(function (Project $project): void {
            ProjectActivity::recordProjectUpdate($project);
        });

        static::saved(function (Project $project): void {
            ProjectChanged::dispatch($project->id);
        });

        static::deleted(function (Project $project): void {
            if (! $project->isForceDeleting()) {
                ProjectActivity::record($project->id, 'project_deleted');
            }

            ProjectChanged::dispatch($project->id);
        });
    }

    /** @return HasMany<ProjectActivity, $this> */
    public function activities(): HasMany
    {
        return $this->hasMany(ProjectActivity::class);
    }

    /** @return BelongsTo<Department, $this> */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /** @return HasMany<Task, $this> */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    /** @return HasMany<Timesheet, $this> */
    public function timesheets(): HasMany
    {
        return $this->hasMany(Timesheet::class);
    }

    /** @return HasMany<Expense, $this> */
    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }
}
