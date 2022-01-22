<?php

namespace App\Models;

use App\Filters\Concerns\HasFilters;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;
    use HasFilters;

    protected $fillable = [
        'code',
        'name',
        'description',
        'quantity',
        'weight',
        'weight_unit_id',
        'price',
        'sale_price',
        'currency_id',
        'disabled_at',
    ];

    public function weightUnit(): BelongsTo
    {
        return $this->belongsTo(WeightUnit::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }
}
