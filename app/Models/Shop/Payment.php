<?php

namespace App\Models\Shop;

use App\Enums\CurrencyCode;
use Database\Factories\Shop\PaymentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    /** @use HasFactory<PaymentFactory> */
    use HasFactory;

    protected $table = 'payments';

    protected $guarded = [];

    protected $casts = [
        'currency' => CurrencyCode::class,
    ];

    /** @return BelongsTo<Order, $this> */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
