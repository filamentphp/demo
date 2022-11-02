<?php

namespace App\Models;

use Filament\Context;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasTenants;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements FilamentUser, HasTenants, MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    /**
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function canAccessFilament(Context $context): bool
    {
        return true;
    }

    public function canAccessTenant(Model $tenant): bool
    {
        return true;
    }

    public function getTenants(Context $context): array | Collection
    {
        return Team::all();
    }
}
