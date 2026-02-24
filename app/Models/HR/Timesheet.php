<?php

namespace App\Models\HR;

use Database\Factories\HR\TimesheetFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Timesheet extends Model
{
    /** @use HasFactory<TimesheetFactory> */
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'timesheets';

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'date',
        'hours' => 'decimal:1',
        'hourly_rate' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'is_billable' => 'boolean',
    ];

    /** @return BelongsTo<Employee, $this> */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    /** @return BelongsTo<Task, $this> */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    /** @return BelongsTo<Project, $this> */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
