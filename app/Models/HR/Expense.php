<?php

namespace App\Models\HR;

use App\Enums\ExpenseCategory;
use App\Enums\ExpenseStatus;
use Database\Factories\HR\ExpenseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Expense extends Model
{
    /** @use HasFactory<ExpenseFactory> */
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'expenses';

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'status' => ExpenseStatus::class,
        'category' => ExpenseCategory::class,
        'total_amount' => 'decimal:2',
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    /** @return BelongsTo<Employee, $this> */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    /** @return BelongsTo<Project, $this> */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /** @return BelongsTo<Employee, $this> */
    public function approvedByEmployee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'approved_by');
    }

    /** @return HasMany<ExpenseLine, $this> */
    public function expenseLines(): HasMany
    {
        return $this->hasMany(ExpenseLine::class);
    }
}
