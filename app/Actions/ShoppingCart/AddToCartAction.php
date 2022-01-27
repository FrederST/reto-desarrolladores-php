<?php

namespace App\Actions\ShoppingCart;

use App\Actions\Action;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class AddToCartAction extends Action
{
    public function execute(array $data, Model $shoppingCartItem): Model
    {
        $product = Product::find($data['product_id']);
        $cartItemQ = auth()->user()->shoppingCart->shoppingCartItems()->where('product_id', $product->id);
        if ($cartItemQ->exists()) {
            $cartItem = $cartItemQ->first();
            $cartItem->quantity += $data['quantity'];
            $cartItem->total += $data['quantity'] * $product->sale_price;
            $cartItem->save();
            return $shoppingCartItem;
        }

        $shoppingCartItem->shopping_cart_id = auth()->user()->shoppingCart->id;
        $shoppingCartItem->product_id = $product->id;
        $shoppingCartItem->quantity = $data['quantity'];
        $shoppingCartItem->total = $data['quantity'] * $product->sale_price;
        $shoppingCartItem->save();

        return $shoppingCartItem;
    }
}
