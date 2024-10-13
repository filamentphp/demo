<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'email',
        'message',
        'entreprise',
        'telephone',
        'ville',
        'nom_entreprise',
        'num_pattente',
    ];
}
