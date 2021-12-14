<?php

declare(strict_types=1);

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class Discount extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'shop_discounts';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'type',
        'value',
        'usage_limit',
        'usage_limit_per_customer',
        'total_usage',
        'starts_at',
        'ends_at',
        'is_visible',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'is_visible' => 'boolean',
        'starts_at' => 'date',
        'ends_at' => 'date',
    ];
}
