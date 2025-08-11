<?php

namespace App\Models\Shop;

use Database\Factories\Shop\OrderItemFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    /** @use HasFactory<OrderItemFactory> */
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'shop_order_items';
}
