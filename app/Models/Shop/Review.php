<?php

declare(strict_types=1);

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Review extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'shop_reviews';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'content',
        'rating',
        'is_visible',
        'is_recommended',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'shop_customer_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'shop_product_id');
    }
}
