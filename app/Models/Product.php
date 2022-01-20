<?php

namespace App\Models;

use App\Filters\Concerns\HasFilters;
use App\Helpers\CurrencyHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

    public function weightUnit(): HasOne
    {
        return $this->hasOne(WeightUnit::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function getSalePriceAttribute(int $value)
    {
        return CurrencyHelper::toCurrencyFormat($value);
    }
}
