<?php

declare(strict_types=1);

namespace App\Models\Shop;

use App\Models\Address;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

final class Customer extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'shop_customers';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'photo',
        'gender',
        'phone',
        'birthday',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'birthday' => 'date',
    ];

    public function address(): MorphOne
    {
        return $this->morphOne(Address::class, 'addressable');
    }
}
