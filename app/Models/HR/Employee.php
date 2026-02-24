<?php

namespace App\Models\HR;

use App\Enums\EmploymentType;
use Database\Factories\HR\EmployeeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    /** @use HasFactory<EmployeeFactory> */
    use HasFactory;

    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'employees';

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'employment_type' => EmploymentType::class,
        'is_active' => 'boolean',
        'skills' => 'array',
        'metadata' => 'array',
        'salary' => 'decimal:2',
        'hourly_rate' => 'decimal:2',
        'date_of_birth' => 'date',
        'hire_date' => 'date',
    ];

    /** @return BelongsTo<Department, $this> */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /** @return HasMany<LeaveRequest, $this> */
    public function leaveRequests(): HasMany
    {
        return $this->hasMany(LeaveRequest::class);
    }

    /** @return HasMany<LeaveRequest, $this> */
    public function approvedLeaveRequests(): HasMany
    {
        return $this->hasMany(LeaveRequest::class, 'approver_id');
    }

    /** @return HasMany<Task, $this> */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'assigned_to');
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
