<?php

namespace App\Models;

use Filament\Models\Contracts\HasCurrentTenantLabel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spark\Billable;

class Team extends Model implements HasCurrentTenantLabel
{
    use HasFactory;

    public function getCurrentTenantLabel(): string
    {
        return 'Current team';
    }
}
