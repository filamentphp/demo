<?php

namespace App\Models\Shop;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'shop_orders';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'number',
        'total_price',
        'status',
        'currency',
        'shipping_price',
        'shipping_method',
        'notes',
        'first_name',
        'last_name',
        'company_name',
        'country',
        'address1',
        'address2',
        'city',
        'state',
        'zip',
        'phone',
        'email',
        'order_notes',
        'create_account',
        'different_address',
    ];

    protected $casts = [
        'status' => OrderStatus::class,
    ];

    /** @return MorphOne<OrderAddress> */
    public function address(): MorphOne
    {
        return $this->morphOne(OrderAddress::class, 'addressable');
    }

    /** @return BelongsTo<Customer,self> */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'shop_customer_id');
    }

    /** @return HasMany<OrderItem> */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'shop_order_id');
    }

    /** @return HasMany<Payment> */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
