<?php

declare(strict_types=1);

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class OrderItem extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'shop_order_items';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'shop_product_id',
        'qty',
        'unit_price',
    ];
}
