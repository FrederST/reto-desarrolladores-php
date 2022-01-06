<?php

namespace App\Actions\ShoppingCart;

use App\Actions\Action;
use App\Helpers\CurrencyHelper;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class StorageAction extends Action
{
    public function execute(array $data, Model $shoppingCart): Model
    {
        $product = Product::find($data['product_id']);
        $product->quantity -= $data['quantity'];
        $shoppingCart->user_id = auth()->user()->id;
        $shoppingCart->product_id = $product->id;
        $shoppingCart->quantity = $data['quantity'];
        $shoppingCart->total = CurrencyHelper::parseCurrency($data['quantity'] * $product->sale_price);
        $shoppingCart->save();
        $product->save();
        return $shoppingCart;
    }

}
