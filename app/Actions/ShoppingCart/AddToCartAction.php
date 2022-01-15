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
        $product->quantity -= $data['quantity'];
        $shoppingCartItem->shopping_cart_id = auth()->user()->shoppingCart->id;
        $shoppingCartItem->product_id = $product->id;
        $shoppingCartItem->quantity = $data['quantity'];
        $shoppingCartItem->total = $data['quantity'] * $product->sale_price;
        $shoppingCartItem->save();
        $product->save();

        return $shoppingCartItem;
    }
}
