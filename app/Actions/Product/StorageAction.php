<?php

namespace App\Actions\Product;

use App\Actions\Action;
use App\Helpers\CurrencyHelper;
use Illuminate\Database\Eloquent\Model;

class StorageAction extends Action
{
    public function execute(array $data, Model $product): Model
    {
        $product->name = $data['name'];
        $product->description = $data['description'];
        $product->quantity = $data['quantity'];
        $product->weight = $data['weight'];
        $product->weight_unit_id = $data['weight_unit_id'];
        $product->price = $data['price'];
        $product->currency_id = CurrencyHelper::getDefaultCurrency()->id;
        $product->sale_price = CurrencyHelper::parseCurrency($data['sale_price']);

        $product->save();

        return $product;
    }
}
