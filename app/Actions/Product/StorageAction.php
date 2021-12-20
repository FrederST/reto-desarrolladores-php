<?php

namespace App\Actions\Product;

use App\Actions\Action;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class StorageAction extends Action
{
    public function execute(array $data, Model $product): Model
    {
        $product->name = $data['name'];
        $product->description = $data['description'];
        $product->quantity = $data['quantity'];
        $product->weight = $data['weight'];
        $product->price = $data['price'];
        $product->sale_price = $data['sale_price'];
        $product->save();

        Log::channel('product')->info('Product Created', $product->toArray());
        return $product;
    }
}
