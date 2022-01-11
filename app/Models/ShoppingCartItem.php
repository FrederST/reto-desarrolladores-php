<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShoppingCartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'shopping_cart_id',
        'product_id',
        'quantity',
        'total'
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

}
