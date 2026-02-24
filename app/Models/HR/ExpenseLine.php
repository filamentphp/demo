<?php

namespace App\Models\HR;

use Database\Factories\HR\ExpenseLineFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExpenseLine extends Model
{
    /** @use HasFactory<ExpenseLineFactory> */
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'expense_lines';

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'date' => 'date',
    ];

    /** @return BelongsTo<Expense, $this> */
    public function expense(): BelongsTo
    {
        return $this->belongsTo(Expense::class);
    }
}
