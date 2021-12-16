<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Address extends Model
{
    use HasFactory;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'country',
        'street',
        'city',
        'state',
        'zip',
    ];

    public function addressable(): MorphTo
    {
        return $this->morphTo();
    }
}
