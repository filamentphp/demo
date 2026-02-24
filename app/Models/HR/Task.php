<?php

namespace App\Models\HR;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use Database\Factories\HR\TaskFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    /** @use HasFactory<TaskFactory> */
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'tasks';

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'status' => TaskStatus::class,
        'priority' => TaskPriority::class,
        'estimated_hours' => 'decimal:1',
        'actual_hours' => 'decimal:1',
        'due_date' => 'date',
        'completed_at' => 'datetime',
        'labels' => 'array',
    ];

    /** @return BelongsTo<Project, $this> */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /** @return BelongsTo<Employee, $this> */
    public function assignee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'assigned_to');
    }

    /** @return HasMany<Timesheet, $this> */
    public function timesheets(): HasMany
    {
        return $this->hasMany(Timesheet::class);
    }
}
