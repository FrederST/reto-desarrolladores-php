<?php

namespace App\Actions\ShoppingCart;

use Illuminate\Database\Eloquent\Model;

class DeleteToCartAction
{
    public function execute(Model $shoppingCartItem): Model
    {
        $product = $shoppingCartItem->product;
        $product->quantity += $shoppingCartItem->quantity;
        $product->save();
        $shoppingCartItem->delete();
        return $shoppingCartItem;
    }
}
