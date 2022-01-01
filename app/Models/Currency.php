<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    protected $fillable = [
        'alphabetic_code',
        'name',
        'entity',
        'minor_unit',
        'numeric_code',
    ];
}
