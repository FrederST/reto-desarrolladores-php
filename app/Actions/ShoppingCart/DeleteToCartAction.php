<?php

namespace App\Actions\ShoppingCart;

use App\Actions\Action;
use App\Helpers\CurrencyHelper;
use App\Models\Product;
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
