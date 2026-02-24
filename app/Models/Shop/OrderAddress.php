<?php

namespace App\Models\Shop;

use App\Enums\CountryCode;
use Database\Factories\Shop\OrderAddressFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class OrderAddress extends Model
{
    /** @use HasFactory<OrderAddressFactory> */
    use HasFactory;

    protected $table = 'order_addresses';

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'country' => CountryCode::class,
        ];
    }

    /** @return MorphTo<Model, $this> */
    public function addressable(): MorphTo
    {
        return $this->morphTo();
    }
}
