<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'quantity',
        'weight',
        'weight_unit_id',
        'price',
        'sale_price',
        'currency_id',
        'status',
    ];

    public function weightUnit(): HasOne
    {
        return $this->hasOne(WeightUnit::class);
    }

    public function currency(): HasOne
    {
        return $this->hasOne(Currency::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }
}
