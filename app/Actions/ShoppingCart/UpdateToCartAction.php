<?php

namespace App\Actions\ShoppingCart;

use App\Actions\Action;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class UpdateToCartAction extends Action
{
    public function execute(array $data, Model $shoppingCartItem): Model
    {
        $product = Product::find($data['product_id']);
        $shoppingCartItem->quantity = $data['quantity'];
        $shoppingCartItem->total = $data['quantity'] * $product->sale_price;
        $shoppingCartItem->save();

        return $shoppingCartItem;
    }
}
