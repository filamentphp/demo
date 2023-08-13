<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Filament\Models\Contracts\HasCurrentTenantLabel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Team extends Model implements HasCurrentTenantLabel
{
    use HasFactory;

    public function getCurrentTenantLabel(): string
    {
        return 'Current team';
    }

    public function players(): HasMany
    {
        return $this->hasMany(Player::class);
    }
}
